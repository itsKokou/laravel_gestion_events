<?php

namespace Tests\Feature;

use App\Models\ActivityLog;
use App\Models\Event;
use App\Models\Order;
use App\Models\Role;
use App\Models\Ticket;
use App\Models\TicketType;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminAnalyticsTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_admin_analytics_json(): void
    {
        $this->get(route('admin.dashboard.data'))->assertRedirect();
    }

    public function test_admin_can_fetch_global_stats_json(): void
    {
        $adminRole = Role::create(['name' => 'Administrateur', 'slug' => 'admin']);
        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@example.test',
            'password' => bcrypt('password'),
        ]);
        $admin->roles()->attach($adminRole);

        $this->actingAs($admin);

        $response = $this->getJson(route('admin.dashboard.data'));
        $response->assertOk();
        $response->assertJsonStructure([
            'total_events',
            'total_orders',
            'total_paid_orders',
            'total_revenue_cents',
            'total_tickets_sold',
            'active_reservations',
            'expired_reservations',
            'reservation_flow_health' => ['stale_pending_orders'],
            'meta' => ['generated_at', 'schema_version'],
        ]);
    }

    public function test_global_stats_total_tickets_sold_excludes_cancelled_tickets(): void
    {
        $adminRole = Role::create(['name' => 'Administrateur', 'slug' => 'admin']);
        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@example.test',
            'password' => bcrypt('password'),
        ]);
        $admin->roles()->attach($adminRole);

        $event = Event::create([
            'name' => 'Soirée Stats',
            'slug' => 'soiree-stats',
            'starts_at' => now()->addDays(3),
            'ends_at' => now()->addDays(3)->addHours(5),
            'venue_name' => 'Club X',
            'venue_address' => '1 rue de la Nuit',
            'min_age' => 18,
            'capacity' => 200,
            'status' => 'published',
            'published_at' => now(),
            'sold_tickets' => 2,
        ]);

        $ticketType = TicketType::create([
            'event_id' => $event->id,
            'code' => 'std',
            'name' => 'Standard',
            'price_cents' => 5000,
            'currency' => 'FCFA',
            'is_active' => true,
            'sort_order' => 0,
            'sold_tickets' => 2,
        ]);

        $order = Order::create([
            'event_id' => $event->id,
            'order_number' => 'ORD-STAT-TIX',
            'customer_email' => 'stats@example.test',
            'customer_phone' => null,
            'status' => 'paid',
            'currency' => 'FCFA',
            'subtotal_cents' => 10_000,
            'addons_total_cents' => 0,
            'total_cents' => 10_000,
            'paid_at' => now(),
        ]);

        Ticket::create([
            'order_id' => $order->id,
            'event_id' => $event->id,
            'ticket_type_id' => $ticketType->id,
            'attendee_first_name' => 'A',
            'attendee_last_name' => 'A',
            'attendee_email' => 'a@example.test',
            'attendee_birthdate' => now()->subYears(20)->format('Y-m-d'),
            'qr_token' => str_repeat('d', 48),
            'issued_at' => now(),
        ]);

        Ticket::create([
            'order_id' => $order->id,
            'event_id' => $event->id,
            'ticket_type_id' => $ticketType->id,
            'attendee_first_name' => 'B',
            'attendee_last_name' => 'B',
            'attendee_email' => 'b@example.test',
            'attendee_birthdate' => now()->subYears(22)->format('Y-m-d'),
            'qr_token' => str_repeat('e', 48),
            'issued_at' => now(),
            'cancelled_at' => now(),
        ]);

        $this->actingAs($admin);

        $response = $this->getJson(route('admin.dashboard.data'));
        $response->assertOk();
        $response->assertJsonPath('total_tickets_sold', 1);
    }

    public function test_reservation_creates_activity_log(): void
    {
        $adminRole = Role::create(['name' => 'Administrateur', 'slug' => 'admin']);
        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@example.test',
            'password' => bcrypt('password'),
        ]);
        $admin->roles()->attach($adminRole);

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

        TicketType::create([
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

        $this->actingAs($admin);
        $this->post(route('public.reservations.store', $event), $payload)->assertRedirect();

        $this->assertDatabaseHas('activity_logs', [
            'type' => 'order.created',
            'event_id' => $event->id,
        ]);
        $this->assertSame(1, ActivityLog::query()->where('type', 'order.created')->count());
    }
}
