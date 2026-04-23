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
        Schema::table('pawn_transactions', function (Blueprint $table) {
            $table->dropColumn('item_data');
        });
    }

    public function down(): void
    {
        Schema::table('pawn_transactions', function (Blueprint $table) {
            $table->longText('item_data');
        });
    }
};
