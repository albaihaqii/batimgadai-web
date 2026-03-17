<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('nasabah', function (Blueprint $table) {
            $table->id();
            $table->string('no_cif', 15)->unique();
            $table->string('nama', 100);
            $table->char('no_ktp', 16)->unique();
            $table->string('no_hp', 20);
            $table->text('alamat');
            $table->foreignId('cabang_id')->constrained('cabang');
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
            $table->date('tgl_bergabung');
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('nasabah');
    }
};