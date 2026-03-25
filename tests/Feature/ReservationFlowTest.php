<?php

namespace Tests\Feature;

use App\Models\Event;
use App\Models\Order;
use App\Models\Ticket;
use App\Models\TicketType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReservationFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_events_index_lists_published_events(): void
    {
        $published = Event::create([
            'name' => 'Soirée Test',
            'slug' => 'soiree-test',
            'starts_at' => now()->addDays(3),
            'ends_at' => now()->addDays(3)->addHours(5),
            'venue_name' => 'Club X',
            'venue_address' => '1 rue de la Nuit',
            'min_age' => 18,
            'capacity' => 200,
            'status' => 'published',
            'published_at' => now(),
        ]);

        Event::create([
            'name' => 'Brouillon',
            'slug' => 'brouillon',
            'starts_at' => now()->addDays(4),
            'ends_at' => now()->addDays(4)->addHours(5),
            'venue_name' => 'Club Y',
            'venue_address' => '2 rue de la Nuit',
            'min_age' => 18,
            'capacity' => 200,
            'status' => 'draft',
        ]);

        $response = $this->get(route('public.events.index'));

        $response->assertStatus(200);
        $response->assertSee($published->name);
        $response->assertDontSee('Brouillon');
    }

    public function test_can_create_order_and_tickets(): void
    {
        $event = Event::create([
            'name' => 'Soirée Test',
            'slug' => 'soiree-test',
            'starts_at' => now()->addDays(3),
            'ends_at' => now()->addDays(3)->addHours(5),
            'venue_name' => 'Club X',
            'venue_address' => '1 rue de la Nuit',
            'min_age' => 18,
            'capacity' => 200,
            'status' => 'published',
            'published_at' => now(),
        ]);

        $ticketType = TicketType::create([
            'event_id' => $event->id,
            'code' => 'normal',
            'name' => 'Tarif normal',
            'price_cents' => 1500_00,
            'currency' => 'FCFA',
            'is_active' => true,
            'sort_order' => 0,
        ]);

        $payload = [
            'customer_email' => 'client@example.test',
            'customer_phone' => '770000000',
            'ticket_type_id' => $ticketType->id,
            'quantity' => 1,
            'attendees' => [
                [
                    'first_name' => 'Awa',
                    'last_name' => 'Diallo',
                    'email' => 'awa@example.test',
                    'phone' => null,
                    'birthdate' => now()->subYears(25)->format('Y-m-d'),
                ],
            ],
            'agree_terms' => '1',
        ];

        $response = $this->post(route('public.reservations.store', $event), $payload);

        $response->assertRedirect();
        $this->assertDatabaseCount('orders', 1);
        $this->assertDatabaseCount('tickets', 1);

        $order = Order::firstOrFail();
        $this->assertSame('pending_payment', $order->status);
        $this->assertNotEmpty($order->order_number);
        $this->assertNotNull($order->expires_at);
        $this->assertTrue($order->expires_at->isFuture());

        $event->refresh();
        $this->assertSame(1, $event->sold_tickets);

        $ticket = Ticket::firstOrFail();
        $this->assertSame($order->id, $ticket->order_id);
        $this->assertSame($event->id, $ticket->event_id);
        $this->assertSame($ticketType->id, $ticket->ticket_type_id);
        $this->assertNotEmpty($ticket->qr_token);

        // Paiement simulé (PAYTECH_MODE=simulate)
        $pay = $this->post(route('public.orders.pay', $order));
        $pay->assertRedirect(route('public.orders.show', $order));

        $order->refresh();
        $this->assertSame('paid', $order->status);

        $qr = $this->get(route('tickets.qr', $ticket));
        $qr->assertOk();
        $this->assertStringContainsString('image/svg+xml', (string) $qr->headers->get('Content-Type'));
    }

    public function test_cannot_reserve_beyond_sold_tickets_capacity(): void
    {
        $event = Event::create([
            'name' => 'Petite salle',
            'slug' => 'petite-salle',
            'starts_at' => now()->addDays(3),
            'ends_at' => now()->addDays(3)->addHours(5),
            'venue_name' => 'Club X',
            'venue_address' => '1 rue de la Nuit',
            'min_age' => 18,
            'capacity' => 1,
            'status' => 'published',
            'published_at' => now(),
        ]);

        TicketType::create([
            'event_id' => $event->id,
            'code' => 'normal',
            'name' => 'Tarif normal',
            'price_cents' => 1500_00,
            'currency' => 'FCFA',
            'is_active' => true,
            'sort_order' => 0,
        ]);

        $payload = fn (string $email) => [
            'customer_email' => $email,
            'customer_phone' => '770000000',
            'quantity' => 1,
            'attendees' => [
                [
                    'first_name' => 'Awa',
                    'last_name' => 'Diallo',
                    'email' => 'awa@example.test',
                    'phone' => null,
                    'birthdate' => now()->subYears(25)->format('Y-m-d'),
                ],
            ],
            'agree_terms' => '1',
        ];

        $this->post(route('public.reservations.store', $event), $payload('a@example.test'))
            ->assertRedirect();

        $this->post(route('public.reservations.store', $event), $payload('b@example.test'))
            ->assertSessionHasErrors('quantity');

        $this->assertDatabaseCount('orders', 1);
        $event->refresh();
        $this->assertSame(1, $event->sold_tickets);
    }
}
