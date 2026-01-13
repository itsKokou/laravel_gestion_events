<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Ticket;
use App\Models\TicketScan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ScannerController extends Controller
{
    public function home()
    {
        $events = Event::query()
            ->where('status', 'published')
            ->orderBy('starts_at')
            ->limit(25)
            ->withCount(['tickets as present_count' => function ($query) {
                $query->whereNotNull('checked_in_at');
            }])
            ->get();

        return view('scanner.home', [
            'events' => $events,
        ]);
    }

    public function scanPage(Event $event)
    {
        abort_unless($event->status === 'published', 404);

        $presentCount = Ticket::query()
            ->where('event_id', $event->id)
            ->whereNotNull('checked_in_at')
            ->count();

        $autoScanToken = session('auto_scan_token');

        return view('scanner.event', [
            'event' => $event,
            'presentCount' => $presentCount,
            'autoScanToken' => $autoScanToken,
        ]);
    }

    public function scanFromUrl(Event $event, string $token)
    {
        abort_unless($event->status === 'published', 404);

        // Rediriger vers la page de scan avec le token pré-rempli et auto-scanné
        return redirect()->route('scanner.event', $event)
            ->with('auto_scan_token', $token);
    }

    public function scan(Request $request, Event $event)
    {
        abort_unless($event->status === 'published', 404);

        $data = $request->validate([
            'qr_token' => ['required', 'string', 'min:10'],
        ]);

        $qrToken = $data['qr_token'];

        return DB::transaction(function () use ($request, $event, $qrToken) {
            $ticket = Ticket::query()
                ->where('qr_token', $qrToken)
                ->lockForUpdate()
                ->first();

            if (! $ticket || $ticket->event_id !== $event->id) {
                TicketScan::create([
                    'ticket_id' => null,
                    'event_id' => $event->id,
                    'qr_token' => $qrToken,
                    'result' => 'invalid',
                    'scanned_at' => now(),
                    'scanner_id' => 'web',
                    'ip_address' => $request->ip(),
                    'user_agent' => (string) $request->userAgent(),
                ]);

                return response()->json([
                    'result' => 'invalid',
                    'message' => "QR code non reconnu.",
                ], 404);
            }

            $ticket->loadMissing('order');

            if ($ticket->cancelled_at !== null || $ticket->order?->status !== 'paid') {
                TicketScan::create([
                    'ticket_id' => $ticket->id,
                    'event_id' => $event->id,
                    'qr_token' => $qrToken,
                    'result' => 'invalid',
                    'scanned_at' => now(),
                    'scanner_id' => 'web',
                    'ip_address' => $request->ip(),
                    'user_agent' => (string) $request->userAgent(),
                ]);

                return response()->json([
                    'result' => 'invalid',
                    'message' => "Billet non valide (annulé ou paiement non confirmé).",
                ], 422);
            }

            if ($ticket->checked_in_at !== null) {
                TicketScan::create([
                    'ticket_id' => $ticket->id,
                    'event_id' => $event->id,
                    'qr_token' => $qrToken,
                    'result' => 'already_used',
                    'scanned_at' => now(),
                    'scanner_id' => 'web',
                    'ip_address' => $request->ip(),
                    'user_agent' => (string) $request->userAgent(),
                ]);

                return response()->json([
                    'result' => 'already_used',
                    'message' => "Billet déjà scanné.",
                    'checked_in_at' => $ticket->checked_in_at->toIso8601String(),
                    'attendee' => [
                        'name' => "{$ticket->attendee_first_name} {$ticket->attendee_last_name}",
                        'email' => $ticket->attendee_email,
                    ],
                ], 200);
            }

            $ticket->checked_in_at = now();
            $ticket->save();

            TicketScan::create([
                'ticket_id' => $ticket->id,
                'event_id' => $event->id,
                'qr_token' => $qrToken,
                'result' => 'valid',
                'scanned_at' => now(),
                'scanner_id' => 'web',
                'ip_address' => $request->ip(),
                'user_agent' => (string) $request->userAgent(),
            ]);

            $presentCount = Ticket::query()
                ->where('event_id', $event->id)
                ->whereNotNull('checked_in_at')
                ->count();

            return response()->json([
                'result' => 'valid',
                'message' => "Entrée autorisée.",
                'attendee' => [
                    'name' => "{$ticket->attendee_first_name} {$ticket->attendee_last_name}",
                    'email' => $ticket->attendee_email,
                ],
                'present_count' => $presentCount,
                'capacity' => $event->capacity,
            ], 200);
        });
    }
}
