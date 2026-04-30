<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jasa_rates', function (Blueprint $table) {
            $table->id();
            $table->enum('tipe', ['umum', 'perhiasan'])->default('umum');
            $table->unsignedBigInteger('min_pinjaman');
            $table->unsignedBigInteger('max_pinjaman')->nullable()->comment('null = tidak terbatas ke atas');
            $table->decimal('jasa_15_hari', 5, 2)->comment('persen untuk 1-15 hari');
            $table->decimal('jasa_30_hari', 5, 2)->comment('persen untuk 16-30 hari');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jasa_rates');
    }
};