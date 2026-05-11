<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mobile_slides', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('image_path');
            $table->unsignedSmallInteger('sort_order')->default(1);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        DB::table('mobile_slides')->insert([
            [
                'title' => 'Beranda Aplikasi',
                'description' => 'Tampilan utama aplikasi mobile BATIM GADAI.',
                'image_path' => 'frontend/images/mockup-1.png',
                'sort_order' => 1,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Simulasi Gadai',
                'description' => 'Nasabah dapat melihat estimasi pinjaman sebelum datang ke outlet.',
                'image_path' => 'frontend/images/mockup-2.png',
                'sort_order' => 2,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Riwayat Gadai',
                'description' => 'Status dan riwayat transaksi gadai dapat dipantau dari aplikasi.',
                'image_path' => 'frontend/images/mockup-3.png',
                'sort_order' => 3,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('mobile_slides');
    }
};
