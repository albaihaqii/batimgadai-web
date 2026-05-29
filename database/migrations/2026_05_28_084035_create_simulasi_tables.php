<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Master persentase gadai per kategori
        Schema::create('simulasi_master', function (Blueprint $table) {
            $table->id();
            $table->enum('kategori', [
                'handphone','laptop','tablet','kamera',
                'elektronik','perhiasan','kendaraan',
                'barang_rumah_tangga','lainnya'
            ])->unique();
            $table->decimal('persen_min', 5, 2)->default(50);
            $table->decimal('persen_max', 5, 2)->default(75);
            $table->text('keterangan')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Kecacatan per kategori
        Schema::create('simulasi_kecacatan', function (Blueprint $table) {
            $table->id();
            $table->enum('kategori', [
                'handphone','laptop','tablet','kamera',
                'elektronik','perhiasan','kendaraan',
                'barang_rumah_tangga','lainnya'
            ]);
            $table->string('label');
            $table->decimal('faktor', 5, 2)->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Kelengkapan per kategori
        Schema::create('simulasi_kelengkapan', function (Blueprint $table) {
            $table->id();
            $table->enum('kategori', [
                'handphone','laptop','tablet','kamera',
                'elektronik','perhiasan','kendaraan',
                'barang_rumah_tangga','lainnya'
            ]);
            $table->string('label');
            $table->decimal('faktor', 5, 2)->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('simulasi_kelengkapan');
        Schema::dropIfExists('simulasi_kecacatan');
        Schema::dropIfExists('simulasi_master');
    }
};