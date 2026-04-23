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
            $table->foreignId('locker_id')->nullable()->constrained('loker')->onDelete('set null')->after('branch_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pawn_transactions', function (Blueprint $table) {
            $table->dropForeign(['locker_id']);
            $table->dropColumn('locker_id');
        });
    }
};
