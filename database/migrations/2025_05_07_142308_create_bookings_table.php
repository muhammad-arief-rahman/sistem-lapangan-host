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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('field_id')->constrained()->onDelete('cascade');
            $table->unsignedBigInteger('field_schedule_id');
            $table->foreign('field_schedule_id')->references('id')->on('field_schedules')->onDelete('cascade');
            $table->enum('type', ['trofeo', 'open_match', 'regular'])->default('open_match');
            $table->enum('status', ['pending', 'confirmed', 'cancelled'])->default('pending');
            $table->text('match_photo_link')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
