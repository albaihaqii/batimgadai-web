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
        Schema::table('jenis_barang', function (Blueprint $table) {
            // Kolom id_categories sudah ada, jadi hanya tambahkan FK constraint
            $table->foreign('id_categories', 'jenis_barang_id_categories_fk')
                ->references('id_categories')
                ->on('categories')
                ->onDelete('restrict')
                ->onUpdate('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jenis_barang', function (Blueprint $table) {
            $table->dropForeignKey('jenis_barang_id_categories_fk');
        });
    }
};
