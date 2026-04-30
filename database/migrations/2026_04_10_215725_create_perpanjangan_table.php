<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('perpanjangan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('gadai_id')->constrained('gadai')->onDelete('cascade');
            $table->foreignId('nasabah_id')->constrained('nasabah')->onDelete('cascade');
            $table->foreignId('officer_id')->constrained('users')->onDelete('cascade');
            $table->string('no_sbg')->nullable();
            $table->decimal('nilai_pinjaman', 15, 2);
            $table->decimal('jasa_persen', 5, 2)->default(5.00);
            $table->decimal('jasa_nominal', 15, 2);
            $table->decimal('denda_persen', 5, 2)->default(0);
            $table->decimal('denda_nominal', 15, 2)->default(0);
            $table->integer('hari_terlambat')->default(0);
            $table->decimal('total_bayar', 15, 2);
            $table->date('tgl_perpanjangan');
            $table->date('tgl_jt_lama');
            $table->date('tgl_jt_baru');
            $table->enum('status_bayar', ['menunggu', 'berhasil', 'gagal'])->default('menunggu');
            $table->enum('metode_bayar', ['tunai', 'midtrans'])->default('tunai');
            $table->string('midtrans_order_id')->nullable()->unique();
            $table->string('midtrans_token')->nullable();
            $table->string('midtrans_url')->nullable();
            $table->json('midtrans_response')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('perpanjangan');
    }
};