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
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('slug')->unique();
            $table->string('title');
            $table->text('description')->nullable();
            $table->dateTime('event_date');
            $table->dateTime('rsvp_deadline');
            $table->string('location_name')->nullable();
            $table->string('location_address');
            $table->text('location_map_url')->nullable();
            $table->string('cover_image')->nullable();
            $table->string('theme')->default('pds-neon');
            $table->boolean('allow_companions')->default(true);
            $table->integer('max_companions')->default(10);
            $table->text('whatsapp_message')->nullable();
            $table->string('status')->default('active'); // active, closed
            $table->boolean('is_unlimited')->default(true);
            $table->timestamps();
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
