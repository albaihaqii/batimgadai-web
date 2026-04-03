<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Ubah type kolom id_categories di jenis_barang agar compatible dengan categories
        Schema::table('jenis_barang', function (Blueprint $table) {
            $table->string('id_categories', 36)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jenis_barang', function (Blueprint $table) {
            $table->string('id_categories')->nullable()->change();
        });
    }
};
