<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->string('no_booking')->unique();
            $table->foreignId('nasabah_id')->constrained('nasabah')->onDelete('cascade');
            $table->foreignId('cabang_id')->constrained('cabang')->onDelete('cascade');
            $table->date('tgl_kunjungan');
            $table->time('jam_kunjungan');
            $table->string('keperluan')->default('Gadai Baru');
            $table->text('catatan_nasabah')->nullable();
            $table->text('catatan_admin')->nullable();
            $table->enum('status', ['menunggu', 'dikonfirmasi', 'ditolak', 'selesai'])->default('menunggu');
            $table->foreignId('diproses_oleh')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('tgl_diproses')->nullable();
            // Data simulasi (opsional, disimpan sebagai snapshot)
            $table->string('kategori_barang')->nullable();
            $table->decimal('harga_pasar', 15, 2)->nullable();
            $table->decimal('estimasi_min', 15, 2)->nullable();
            $table->decimal('estimasi_max', 15, 2)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};