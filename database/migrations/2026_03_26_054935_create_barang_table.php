<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('barang', function (Blueprint $table) {
            $table->id();
            $table->foreignId('nasabah_id')->constrained('nasabah');
            $table->string('nama_barang', 150);
            $table->enum('kategori', [
                'handphone',
                'laptop',
                'tablet',
                'elektronik_lainnya',
                'kendaraan_motor',
                'bpkb_motor',
                'barang_rumah_tangga'
            ]);
            $table->string('merk', 100)->nullable();
            $table->string('tipe_model', 100)->nullable();
            $table->enum('kondisi', ['baik', 'cukup', 'rusak_ringan']);
            $table->text('kelengkapan')->nullable();
            $table->string('foto', 255)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('barang');
    }
};