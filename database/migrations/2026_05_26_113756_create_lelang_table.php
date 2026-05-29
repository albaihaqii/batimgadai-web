<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lelang', function (Blueprint $table) {
            $table->id();
            $table->foreignId('gadai_id')->constrained('gadai')->onDelete('cascade');
            $table->foreignId('nasabah_id')->constrained('nasabah');
            $table->string('no_sbg', 20);
            $table->date('tgl_jatuh_tempo');
            $table->date('tgl_lelang')->nullable();
            $table->decimal('sisa_hutang', 15, 2);
            $table->decimal('harga_terjual', 15, 2)->nullable();
            $table->decimal('selisih', 15, 2)->nullable();
            $table->enum('status_selisih', ['lebih', 'kurang', 'pas'])->nullable();
            $table->text('keterangan')->nullable();
            $table->enum('status', ['proses', 'selesai', 'batal'])->default('proses');
            $table->foreignId('diproses_oleh')->nullable()->constrained('users');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lelang');
    }
};