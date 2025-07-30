<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('fields', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('location');
            $table->char('village_id', 10)->nullable();
            $table->foreign('village_id')->references('id')->on('villages')->onDelete('set null');
            $table->integer('price_per_hour');
            $table->text('description')->nullable();
            $table->enum('availability', ['available', 'not_available'])->default('available');
            $table->text('image')->nullable();
            $table->unsignedBigInteger('manager_id');
            $table->foreign('manager_id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fields');
    }
};
