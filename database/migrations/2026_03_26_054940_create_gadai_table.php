<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('gadai', function (Blueprint $table) {
            $table->id();
            $table->string('no_sbg', 20)->unique()->nullable(); // nullable dulu, diisi saat approve
            $table->foreignId('nasabah_id')->constrained('nasabah');
            $table->foreignId('barang_id')->constrained('barang');
            $table->foreignId('cabang_id')->constrained('cabang');
            $table->unsignedBigInteger('loker_id')->nullable(); // FK ke loker, nullable dulu
            $table->foreignId('officer_id')->constrained('users');
            $table->unsignedBigInteger('admin_id')->nullable(); // FK ke users, nullable dulu
            $table->decimal('nilai_taksiran_awal', 15, 2);
            $table->decimal('nilai_taksiran_akhir', 15, 2)->nullable();
            $table->decimal('nilai_pinjaman', 15, 2)->nullable();
            $table->decimal('jasa_persen', 5, 2)->default(5.00);
            $table->decimal('jasa_nominal', 15, 2)->nullable();
            $table->decimal('total_tebus', 15, 2)->nullable();
            $table->date('tgl_gadai')->nullable();
            $table->date('tgl_jatuh_tempo')->nullable();
            $table->enum('status', [
                'menunggu_approval',
                'disetujui',
                'ditolak',
                'aktif',
                'jatuh_tempo',
                'perpanjangan',
                'lunas',
                'lelang'
            ])->default('menunggu_approval');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('gadai');
    }
};