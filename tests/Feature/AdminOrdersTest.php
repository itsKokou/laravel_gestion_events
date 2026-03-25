<?php

namespace Tests\Feature;

use App\Mail\OrderCancelledMail;
use App\Models\Event;
use App\Models\Order;
use App\Models\Role;
use App\Models\Ticket;
use App\Models\TicketType;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class AdminOrdersTest extends TestCase
{
    use RefreshDatabase;

    private function adminUser(): User
    {
        $adminRole = Role::create(['name' => 'Administrateur', 'slug' => 'admin']);
        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@example.test',
            'password' => bcrypt('password'),
        ]);
        $admin->roles()->attach($adminRole);

        return $admin;
    }

    private function publishedEvent(): Event
    {
        return Event::create([
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
    }

    public function test_guest_cannot_access_admin_orders(): void
    {
        $this->get(route('admin.orders.index'))->assertRedirect();
    }

    public function test_admin_can_view_orders_index(): void
    {
        $this->actingAs($this->adminUser());

        $response = $this->get(route('admin.orders.index'));
        $response->assertOk();
        $response->assertSee('Réservations', false);
    }

    public function test_admin_can_filter_orders_by_status(): void
    {
        $admin = $this->adminUser();
        $event = $this->publishedEvent();

        Order::create([
            'event_id' => $event->id,
            'order_number' => 'ORD-PENDING-1',
            'customer_email' => 'pending@example.test',
            'customer_phone' => null,
            'status' => 'pending_payment',
            'currency' => 'FCF',
            'subtotal_cents' => 1000,
            'addons_total_cents' => 0,
            'total_cents' => 1000,
        ]);

        Order::create([
            'event_id' => $event->id,
            'order_number' => 'ORD-PAID-1',
            'customer_email' => 'paid@example.test',
            'customer_phone' => null,
            'status' => 'paid',
            'currency' => 'FCF',
            'subtotal_cents' => 2000,
            'addons_total_cents' => 0,
            'total_cents' => 2000,
            'paid_at' => now(),
        ]);

        $this->actingAs($admin);

        $r = $this->get(route('admin.orders.index', ['status' => 'paid']));
        $r->assertOk();
        $r->assertSee('ORD-PAID-1', false);
        $r->assertDontSee('ORD-PENDING-1', false);
    }

    public function test_admin_can_view_order_detail(): void
    {
        $admin = $this->adminUser();
        $event = $this->publishedEvent();

        $order = Order::create([
            'event_id' => $event->id,
            'order_number' => 'ORD-DETAIL-1',
            'customer_email' => 'detail@example.test',
            'customer_phone' => '770000000',
            'status' => 'paid',
            'currency' => 'FCF',
            'subtotal_cents' => 2000,
            'addons_total_cents' => 0,
            'total_cents' => 2000,
            'paid_at' => now(),
        ]);

        $this->actingAs($admin);

        $response = $this->get(route('admin.orders.show', $order));
        $response->assertOk();
        $response->assertSee('ORD-DETAIL-1', false);
        $response->assertSee('detail@example.test', false);
        $response->assertSee('Soirée Test', false);
    }

    public function test_admin_can_export_csv(): void
    {
        $admin = $this->adminUser();
        $event = $this->publishedEvent();

        Order::create([
            'event_id' => $event->id,
            'order_number' => 'ORD-CSV-1',
            'customer_email' => 'csv@example.test',
            'customer_phone' => null,
            'status' => 'paid',
            'currency' => 'FCF',
            'subtotal_cents' => 500,
            'addons_total_cents' => 0,
            'total_cents' => 500,
            'paid_at' => now(),
        ]);

        $this->actingAs($admin);

        $response = $this->get(route('admin.orders.export'));
        $response->assertOk();
        $response->assertHeader('content-disposition');
        $this->assertStringContainsString('ORD-CSV-1', $response->streamedContent());
        $this->assertStringContainsString('csv@example.test', $response->streamedContent());
    }

    public function test_admin_cancel_one_ticket_updates_order_totals_and_releases_quota(): void
    {
        Mail::fake();

        $admin = $this->adminUser();
        $event = $this->publishedEvent();
        $event->update(['sold_tickets' => 2]);

        $ticketType = TicketType::create([
            'event_id' => $event->id,
            'code' => 'std',
            'name' => 'Standard',
            'price_cents' => 5000,
            'currency' => 'FCF',
            'is_active' => true,
            'sort_order' => 0,
            'sold_tickets' => 2,
        ]);

        $order = Order::create([
            'event_id' => $event->id,
            'order_number' => 'ORD-TWO-TIX',
            'customer_email' => 'deux@example.test',
            'customer_phone' => null,
            'status' => 'paid',
            'currency' => 'FCF',
            'subtotal_cents' => 10_000,
            'addons_total_cents' => 0,
            'total_cents' => 10_000,
            'paid_at' => now(),
        ]);

        $t1 = Ticket::create([
            'order_id' => $order->id,
            'event_id' => $event->id,
            'ticket_type_id' => $ticketType->id,
            'attendee_first_name' => 'Un',
            'attendee_last_name' => 'Un',
            'attendee_email' => 'un@example.test',
            'attendee_birthdate' => now()->subYears(20)->format('Y-m-d'),
            'qr_token' => str_repeat('b', 48),
            'issued_at' => now(),
        ]);

        $t2 = Ticket::create([
            'order_id' => $order->id,
            'event_id' => $event->id,
            'ticket_type_id' => $ticketType->id,
            'attendee_first_name' => 'Deux',
            'attendee_last_name' => 'Deux',
            'attendee_email' => 'deux@example.test',
            'attendee_birthdate' => now()->subYears(22)->format('Y-m-d'),
            'qr_token' => str_repeat('c', 48),
            'issued_at' => now(),
        ]);

        $this->actingAs($admin);
        $this->post(route('admin.orders.tickets.cancel', [$order, $t1]))->assertRedirect();

        $order->refresh();
        $this->assertSame(5000, $order->subtotal_cents);
        $this->assertSame(5000, $order->total_cents);
        $this->assertSame('paid', $order->status);

        $ticketType->refresh();
        $this->assertSame(1, $ticketType->sold_tickets);

        $event->refresh();
        $this->assertSame(1, $event->sold_tickets);

        Mail::assertNothingSent();
    }

    public function test_admin_cancel_order_sends_cancellation_email_to_customer(): void
    {
        Mail::fake();

        $admin = $this->adminUser();
        $event = $this->publishedEvent();

        $order = Order::create([
            'event_id' => $event->id,
            'order_number' => 'ORD-MAIL-CANCEL',
            'customer_email' => 'client@example.test',
            'customer_phone' => null,
            'status' => 'paid',
            'currency' => 'FCF',
            'subtotal_cents' => 1000,
            'addons_total_cents' => 0,
            'total_cents' => 1000,
            'paid_at' => now(),
        ]);

        $this->actingAs($admin);

        $this->post(route('admin.orders.cancel', $order))->assertRedirect();

        Mail::assertSent(OrderCancelledMail::class, function (OrderCancelledMail $mail) use ($order) {
            return (int) $mail->order->id === (int) $order->id
                && $mail->hasTo('client@example.test');
        });
    }
}
