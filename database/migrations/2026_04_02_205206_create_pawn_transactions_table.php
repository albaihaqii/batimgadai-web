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
        Schema::create('pawn_transactions', function (Blueprint $table) {
            $table->id();
            $table->string('no_sbg')->unique();
            $table->foreignId('customer_id')->constrained('nasabah')->onDelete('cascade');
            $table->json('item_data'); // data barang
            $table->json('item_photos')->nullable(); // array of photo paths
            $table->decimal('officer_appraisal_min', 15, 2);
            $table->decimal('officer_appraisal_max', 15, 2);
            $table->decimal('loan_amount', 15, 2);
            $table->decimal('final_appraisal', 15, 2)->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected', 'completed'])->default('pending');
            $table->foreignId('branch_id')->constrained('cabang')->onDelete('cascade');
            $table->foreignId('officer_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('admin_id')->nullable()->constrained('users')->onDelete('set null');
            $table->date('transaction_date');
            $table->date('approval_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pawn_transactions');
    }
};
