<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ticket_types', function (Blueprint $table) {
            $table->unsignedInteger('sold_tickets')->default(0)->after('quantity_limit');
        });

        $driver = Schema::getConnection()->getDriverName();

        if ($driver === 'mysql' || $driver === 'mariadb') {
            DB::statement('
                UPDATE ticket_types tt
                SET sold_tickets = (
                    SELECT COUNT(*) FROM tickets t
                    WHERE t.ticket_type_id = tt.id AND t.cancelled_at IS NULL
                )
            ');
        } else {
            $ticketTypeIds = DB::table('ticket_types')->pluck('id');
            foreach ($ticketTypeIds as $ticketTypeId) {
                $sold = (int) DB::table('tickets')
                    ->where('ticket_type_id', $ticketTypeId)
                    ->whereNull('cancelled_at')
                    ->count();
                DB::table('ticket_types')
                    ->where('id', $ticketTypeId)
                    ->update(['sold_tickets' => $sold]);
            }
        }
    }

    public function down(): void
    {
        Schema::table('ticket_types', function (Blueprint $table) {
            $table->dropColumn('sold_tickets');
        });
    }
};
