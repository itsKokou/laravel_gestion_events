<?php

namespace Database\Seeders;

use App\Models\Addon;
use App\Models\Event;
use App\Models\TicketType;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class DemoEventSeeder extends Seeder
{
    private const HERO_IMAGE = 'events/hero-images/eRCTzgMyzXV6Fy1d8NFQw931TceytRrp9sFcF5BQ.jpg';

    private const SLUG = 'neon-pulse-night';

    public function run(): void
    {
        $startsAt = Carbon::now()->addWeeks(3)->setTime(22, 0);
        $endsAt = $startsAt->copy()->addHours(6);

        $admin = User::query()
            ->whereHas('roles', fn ($q) => $q->where('slug', 'admin'))
            ->first();

        $event = Event::updateOrCreate(
            ['slug' => self::SLUG],
            [
                'created_by_user_id' => $admin?->id,
                'name' => 'Neon Pulse Night',
                'starts_at' => $startsAt,
                'ends_at' => $endsAt,
                'venue_name' => 'Le Onyx Lounge',
                'venue_address' => 'Almadies, Dakar, Sénégal',
                'theme' => 'Électro · Club',
                'description' => <<<'TEXT'
Une nuit immersive au cœur de l'électro : lumières néon, performances live et ambiance premium.

Au programme : DJ sets, show visuel et espaces VIP. Réservez tôt pour profiter des tarifs les plus avantageux — les places partent vite.
TEXT,
                'min_age' => 18,
                'capacity' => 500,
                'sold_tickets' => 0,
                'sales_ends_at' => $startsAt->copy()->subHour(),
                'hero_image_path' => self::HERO_IMAGE,
                'status' => 'published',
                'published_at' => now(),
                'archived_at' => null,
            ]
        );

        $ticketTypes = [
            [
                'code' => 'early-bird',
                'name' => 'Early Bird',
                'price_cents' => 5_000,
                'quantity_limit' => 100,
                'sales_starts_at' => now()->subDays(45),
                'sales_ends_at' => now()->subDays(15),
                'is_active' => true,
                'sort_order' => 0,
            ],
            [
                'code' => 'standard',
                'name' => 'Standard',
                'price_cents' => 7_500,
                'quantity_limit' => 250,
                'sales_starts_at' => now()->subDays(14),
                'sales_ends_at' => $startsAt->copy()->subDays(14),
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'code' => 'vip',
                'name' => 'VIP',
                'price_cents' => 15_000,
                'quantity_limit' => 80,
                'sales_starts_at' => $startsAt->copy()->subDays(14),
                'sales_ends_at' => $startsAt->copy()->subDays(3),
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'code' => 'last-minute',
                'name' => 'Dernière minute',
                'price_cents' => 10_000,
                'quantity_limit' => 70,
                'sales_starts_at' => $startsAt->copy()->subDays(3),
                'sales_ends_at' => $startsAt->copy()->subHour(),
                'is_active' => true,
                'sort_order' => 3,
            ],
        ];

        foreach ($ticketTypes as $data) {
            TicketType::updateOrCreate(
                [
                    'event_id' => $event->id,
                    'code' => $data['code'],
                ],
                [
                    ...$data,
                    'event_id' => $event->id,
                    'currency' => 'FCFA',
                    'sold_tickets' => 0,
                ]
            );
        }

        TicketType::query()
            ->where('event_id', $event->id)
            ->whereNotIn('code', array_column($ticketTypes, 'code'))
            ->delete();

        $addons = [
            [
                'code' => 'table-vip',
                'name' => 'Table VIP (6 personnes)',
                'price_cents' => 75_000,
            ],
            [
                'code' => 'bottle-pack',
                'name' => 'Pack bouteilles premium',
                'price_cents' => 45_000,
            ],
        ];

        foreach ($addons as $data) {
            Addon::updateOrCreate(
                [
                    'event_id' => $event->id,
                    'code' => $data['code'],
                ],
                [
                    ...$data,
                    'event_id' => $event->id,
                    'currency' => 'FCFA',
                    'is_active' => true,
                ]
            );
        }

        Addon::query()
            ->where('event_id', $event->id)
            ->whereNotIn('code', array_column($addons, 'code'))
            ->delete();
    }
}
