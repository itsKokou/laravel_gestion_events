<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained('events')->cascadeOnDelete();

            $table->string('order_number')->unique();

            $table->string('customer_email');
            $table->string('customer_phone')->nullable();

            $table->string('status')->default('pending_payment'); // pending_payment|paid|cancelled|failed
            $table->char('currency', 3)->default('XOF');

            $table->unsignedInteger('subtotal_cents')->default(0);
            $table->unsignedInteger('addons_total_cents')->default(0);
            $table->unsignedInteger('total_cents')->default(0);

            $table->dateTime('agreed_terms_at')->nullable();

            $table->string('payment_provider')->nullable(); // paytech
            $table->string('payment_reference')->nullable();
            $table->dateTime('paid_at')->nullable();
            $table->dateTime('cancelled_at')->nullable();

            $table->json('metadata')->nullable();
            $table->timestamps();

            $table->index(['event_id', 'status']);
            $table->index(['created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
