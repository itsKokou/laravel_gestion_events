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
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('created_by_user_id')->nullable()->constrained('users')->nullOnDelete();

            $table->string('name');
            $table->string('slug')->unique();

            $table->dateTime('starts_at');
            $table->dateTime('ends_at');

            $table->string('venue_name');
            $table->string('venue_address');

            $table->string('theme')->nullable();
            $table->text('description')->nullable();

            $table->unsignedTinyInteger('min_age')->default(18);
            $table->unsignedInteger('capacity');

            $table->dateTime('sales_ends_at')->nullable();
            $table->string('hero_image_path')->nullable();

            $table->string('status')->default('draft'); // draft|published|archived
            $table->dateTime('published_at')->nullable();
            $table->dateTime('archived_at')->nullable();

            $table->timestamps();

            $table->index(['status', 'starts_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
