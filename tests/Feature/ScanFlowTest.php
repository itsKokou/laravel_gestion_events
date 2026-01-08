<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Event;
use App\Models\Order;
use App\Models\Ticket;
use App\Models\TicketType;
use Tests\TestCase;

class ScanFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_scan_returns_valid_then_already_used(): void
    {
        $event = Event::create([
            'name' => 'Soirée Scan',
            'slug' => 'soiree-scan',
            'starts_at' => now()->addDays(1),
            'ends_at' => now()->addDays(1)->addHours(5),
            'venue_name' => 'Club X',
            'venue_address' => '1 rue de la Nuit',
            'min_age' => 18,
            'capacity' => 10,
            'status' => 'published',
            'published_at' => now(),
        ]);

        $ticketType = TicketType::create([
            'event_id' => $event->id,
            'code' => 'normal',
            'name' => 'Tarif normal',
            'price_cents' => 1500_00,
            'currency' => 'XOF',
            'is_active' => true,
            'sort_order' => 0,
        ]);

        $order = Order::create([
            'event_id' => $event->id,
            'order_number' => 'WE-20260108-ABCDEF',
            'customer_email' => 'client@example.test',
            'status' => 'paid',
            'currency' => 'XOF',
            'subtotal_cents' => 1500_00,
            'addons_total_cents' => 0,
            'total_cents' => 1500_00,
            'agreed_terms_at' => now(),
            'paid_at' => now(),
        ]);

        $ticket = Ticket::create([
            'order_id' => $order->id,
            'event_id' => $event->id,
            'ticket_type_id' => $ticketType->id,
            'attendee_first_name' => 'Awa',
            'attendee_last_name' => 'Diallo',
            'attendee_email' => 'awa@example.test',
            'attendee_birthdate' => now()->subYears(25)->format('Y-m-d'),
            'qr_token' => str_repeat('a', 48),
            'issued_at' => now(),
        ]);

        $first = $this->postJson(route('scanner.scan', $event), ['qr_token' => $ticket->qr_token]);
        $first->assertOk()->assertJson([
            'result' => 'valid',
        ]);

        $ticket->refresh();
        $this->assertNotNull($ticket->checked_in_at);

        $second = $this->postJson(route('scanner.scan', $event), ['qr_token' => $ticket->qr_token]);
        $second->assertOk()->assertJson([
            'result' => 'already_used',
        ]);
    }

    public function test_scan_returns_invalid_for_unknown_token(): void
    {
        $event = Event::create([
            'name' => 'Soirée Scan',
            'slug' => 'soiree-scan',
            'starts_at' => now()->addDays(1),
            'ends_at' => now()->addDays(1)->addHours(5),
            'venue_name' => 'Club X',
            'venue_address' => '1 rue de la Nuit',
            'min_age' => 18,
            'capacity' => 10,
            'status' => 'published',
            'published_at' => now(),
        ]);

        $res = $this->postJson(route('scanner.scan', $event), ['qr_token' => str_repeat('b', 48)]);
        $res->assertStatus(404)->assertJson([
            'result' => 'invalid',
        ]);
    }
}
