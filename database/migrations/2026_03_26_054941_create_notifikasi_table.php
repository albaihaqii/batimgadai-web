<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notifikasi', function (Blueprint $table) {
            $table->id();
            $table->enum('tipe_penerima', ['user', 'nasabah']);
            $table->unsignedBigInteger('penerima_id'); // ID dari users atau nasabah
            $table->string('judul', 150);
            $table->text('pesan');
            $table->enum('tipe_notif', [
                'pengajuan_gadai',
                'approval_gadai',
                'jatuh_tempo',
                'perpanjangan',
                'pelunasan',
                'booking_kunjungan',
                'info'
            ]);
            $table->string('referensi_tipe', 50)->nullable(); // nama tabel referensi
            $table->unsignedBigInteger('referensi_id')->nullable(); // ID record terkait
            $table->tinyInteger('is_read')->default(0); // 0 = belum dibaca
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifikasi');
    }
};