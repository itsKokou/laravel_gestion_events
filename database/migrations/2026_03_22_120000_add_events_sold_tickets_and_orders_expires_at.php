<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->unsignedInteger('sold_tickets')->default(0)->after('capacity');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->dateTime('expires_at')->nullable()->after('agreed_terms_at');
            $table->index(['status', 'expires_at']);
        });

        $driver = Schema::getConnection()->getDriverName();

        if ($driver === 'mysql' || $driver === 'mariadb') {
            DB::statement('
                UPDATE events e
                SET sold_tickets = (
                    SELECT COUNT(*) FROM tickets t
                    WHERE t.event_id = e.id AND t.cancelled_at IS NULL
                )
            ');
        } else {
            $eventIds = DB::table('events')->pluck('id');
            foreach ($eventIds as $eventId) {
                $sold = (int) DB::table('tickets')
                    ->where('event_id', $eventId)
                    ->whereNull('cancelled_at')
                    ->count();
                DB::table('events')->where('id', $eventId)->update(['sold_tickets' => $sold]);
            }
        }
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropIndex(['status', 'expires_at']);
            $table->dropColumn('expires_at');
        });

        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn('sold_tickets');
        });
    }
};
