<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpsertEventRequest;
use App\Models\Event;
use App\Models\TicketType;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AdminEventController extends Controller
{
    public function index()
    {
        $events = Event::query()->orderByDesc('starts_at')->paginate(20);

        return view('admin.events.index', [
            'events' => $events,
        ]);
    }

    public function create()
    {
        return view('admin.events.form', [
            'event' => new Event(),
        ]);
    }

    /**
     * Génère un slug unique à partir du nom
     */
    private function generateUniqueSlug(string $name, ?int $excludeId = null): string
    {
        $baseSlug = Str::slug($name);
        $slug = $baseSlug;
        $counter = 1;

        while (
            Event::where('slug', $slug)
                ->when($excludeId, fn($q) => $q->where('id', '!=', $excludeId))
                ->exists()
        ) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }

    public function store(UpsertEventRequest $request)
    {
        $data = $request->validated();

        // Génération automatique du slug
        $slug = $this->generateUniqueSlug($data['name']);

        // Gestion de l'upload d'image
        $heroImagePath = null;
        if ($request->hasFile('hero_image')) {
            $heroImagePath = $request->file('hero_image')->store('events/hero-images', 'public');
        }

        // Extraire les tarifs des données
        $ticketTypes = $data['ticket_types'] ?? [];
        unset($data['ticket_types']);

        $event = Event::create([
            ...$data,
            'slug' => $slug,
            'hero_image_path' => $heroImagePath,
            'published_at' => $data['status'] === 'published' ? now() : null,
            'archived_at' => $data['status'] === 'archived' ? now() : null,
        ]);

        // Créer les tarifs
        foreach ($ticketTypes as $index => $ticketTypeData) {
            TicketType::create([
                'event_id' => $event->id,
                'name' => $ticketTypeData['name'],
                'code' => 'ticket-' . $event->id . '-' . $index, // Code simple pour la compatibilité avec la base de données
                'price_cents' => $ticketTypeData['price_cents'],
                'currency' => $ticketTypeData['currency'] ?? 'XOF',
                'quantity_limit' => $ticketTypeData['quantity_limit'] ?? null,
                'sales_starts_at' => $ticketTypeData['sales_starts_at'],
                'sales_ends_at' => $ticketTypeData['sales_ends_at'],
                'is_active' => isset($ticketTypeData['is_active']) && $ticketTypeData['is_active'] == '1',
                'sort_order' => $ticketTypeData['sort_order'] ?? 0,
            ]);
        }

        return redirect()->route('admin.events.index')->with('success', 'Soirée créée avec succès !');
    }

    public function edit(Event $event)
    {
        $event->load('ticketTypes');

        return view('admin.events.form', [
            'event' => $event,
        ]);
    }

    public function update(UpsertEventRequest $request, Event $event)
    {
        $data = $request->validated();

        // Génération automatique du slug si le nom a changé
        if ($event->name !== $data['name']) {
            $data['slug'] = $this->generateUniqueSlug($data['name'], $event->id);
        }

        // Gestion de l'upload d'image
        if ($request->hasFile('hero_image')) {
            // Supprimer l'ancienne image si elle existe
            if ($event->hero_image_path && Storage::disk('public')->exists($event->hero_image_path)) {
                Storage::disk('public')->delete($event->hero_image_path);
            }
            $data['hero_image_path'] = $request->file('hero_image')->store('events/hero-images', 'public');
        }

        // Extraire les tarifs des données
        $ticketTypes = $data['ticket_types'] ?? [];
        unset($data['ticket_types']);

        $event->fill($data);

        if ($data['status'] === 'published' && $event->published_at === null) {
            $event->published_at = now();
        }
        if ($data['status'] !== 'published') {
            $event->published_at = null;
        }
        if ($data['status'] === 'archived' && $event->archived_at === null) {
            $event->archived_at = now();
        }
        if ($data['status'] !== 'archived') {
            $event->archived_at = null;
        }

        $event->save();

        // Supprimer les anciens tarifs et créer les nouveaux
        $event->ticketTypes()->delete();

        foreach ($ticketTypes as $index => $ticketTypeData) {
            TicketType::create([
                'event_id' => $event->id,
                'name' => $ticketTypeData['name'],
                'code' => 'ticket-' . $event->id . '-' . $index, // Code simple pour la compatibilité avec la base de données
                'price_cents' => $ticketTypeData['price_cents'],
                'currency' => $ticketTypeData['currency'] ?? 'XOF',
                'quantity_limit' => $ticketTypeData['quantity_limit'] ?? null,
                'sales_starts_at' => $ticketTypeData['sales_starts_at'],
                'sales_ends_at' => $ticketTypeData['sales_ends_at'],
                'is_active' => isset($ticketTypeData['is_active']) && $ticketTypeData['is_active'] == '1',
                'sort_order' => $ticketTypeData['sort_order'] ?? 0,
            ]);
        }

        return redirect()->route('admin.events.edit', $event)->with('status', 'Soirée mise à jour.');
    }
}
