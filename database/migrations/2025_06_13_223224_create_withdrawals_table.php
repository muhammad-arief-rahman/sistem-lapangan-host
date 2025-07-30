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
        Schema::create('withdrawals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->integer('amount')->default(0);
            $table->string('account_type');
            $table->string('account_number');
            $table->string('account_name');
            $table->enum('status', ['pending', 'completed', 'failed'])->default('pending');
            $table->text('transfer_proof')->nullable();
            $table->text('notes')->nullable();
            $table->dateTime('approved_at')->nullable();
            $table->string('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('withdrawals');
    }
};
