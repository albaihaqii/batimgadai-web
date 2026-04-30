<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('approval_gadai', function (Blueprint $table) {
            $table->id();
            $table->foreignId('gadai_id')->unique()->constrained('gadai');
            $table->unsignedBigInteger('admin_id')->nullable(); // nullable sampai diproses
            $table->enum('status', ['menunggu', 'disetujui', 'ditolak'])->default('menunggu');
            $table->decimal('nilai_final', 15, 2)->nullable();
            $table->text('catatan')->nullable();
            $table->timestamp('tgl_diproses')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('approval_gadai');
    }
};