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
        Schema::create('extension_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pawn_transaction_id')->constrained('pawn_transactions')->onDelete('cascade');
            $table->date('due_date');
            $table->decimal('penalty', 15, 2)->default(0);
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->date('extension_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('extension_transactions');
    }
};
