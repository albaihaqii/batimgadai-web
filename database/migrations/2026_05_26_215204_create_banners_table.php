<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('banners', function (Blueprint $table) {
            $table->id();
            $table->enum('tipe', ['landing', 'mobile'])->default('mobile');
            $table->string('judul');
            $table->string('subjudul')->nullable();
            $table->text('deskripsi')->nullable();
            $table->string('teks_tombol')->nullable();
            $table->string('url_tombol')->nullable();
            $table->string('foto');
            $table->string('url_link')->nullable();
            $table->unsignedBigInteger('cabang_id')->nullable();
            $table->integer('urutan')->default(1);
            $table->boolean('is_active')->default(false);
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();

            $table->foreign('cabang_id')->references('id')->on('cabang')->onDelete('set null');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('banners');
    }
};