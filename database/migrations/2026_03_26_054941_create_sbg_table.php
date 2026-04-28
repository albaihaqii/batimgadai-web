<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sbg', function (Blueprint $table) {
            $table->id();
            $table->string('no_sbg', 20)->unique();
            $table->foreignId('nasabah_id')->constrained('nasabah');
            $table->foreignId('gadai_id')->constrained('gadai');
            $table->enum('tipe', ['gadai', 'perpanjangan', 'pelunasan']);
            $table->unsignedBigInteger('referensi_id'); // ID dari tabel tipe terkait
            $table->date('tgl_transaksi');
            $table->string('qr_token', 100)->unique(); // UUID untuk verifikasi QR
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sbg');
    }
};