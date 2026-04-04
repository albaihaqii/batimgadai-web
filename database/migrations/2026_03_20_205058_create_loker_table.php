<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('loker', function (Blueprint $table) {
            $table->id();
            $table->string('kode_loker', 30)->unique();
            $table->foreignId('cabang_id')->constrained('cabang');
            $table->char('rak', 1);
            $table->enum('status', ['kosong', 'terisi'])->default('kosong');
            $table->unsignedBigInteger('gadai_id')->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('loker');
    }
};