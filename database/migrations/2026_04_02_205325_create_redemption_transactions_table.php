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
        Schema::create('redemption_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pawn_transaction_id')->constrained('pawn_transactions')->onDelete('cascade');
            $table->decimal('total_loan', 15, 2);
            $table->decimal('interest', 15, 2)->default(0);
            $table->decimal('penalty', 15, 2)->default(0);
            $table->decimal('total_payment', 15, 2);
            $table->enum('status', ['pending', 'completed'])->default('pending');
            $table->date('redemption_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('redemption_transactions');
    }
};
