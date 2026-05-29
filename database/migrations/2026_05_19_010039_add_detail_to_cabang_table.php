<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cabang', function (Blueprint $table) {
            $table->decimal('latitude', 10, 7)->nullable()->after('alamat');
            $table->decimal('longitude', 10, 7)->nullable()->after('latitude');
            $table->string('hari_buka', 50)->default('Senin - Sabtu')->after('longitude');
            $table->string('jam_buka', 10)->default('07.00')->after('hari_buka');
            $table->string('jam_tutup', 10)->default('17.00')->after('jam_buka');
        });
    }

    public function down(): void
    {
        Schema::table('cabang', function (Blueprint $table) {
            $table->dropColumn(['latitude', 'longitude', 'hari_buka', 'jam_buka', 'jam_tutup']);
        });
    }
};