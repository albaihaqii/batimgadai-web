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
            $table->string('item_name')->nullable()->after('customer_id');
            $table->longText('item_description')->nullable()->after('item_name');
            $table->string('item_category')->nullable()->after('item_description');
            $table->string('item_condition')->nullable()->after('item_category');
            $table->text('item_completeness')->nullable()->after('item_condition');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pawn_transactions', function (Blueprint $table) {
            $table->dropColumn(['item_name', 'item_description', 'item_category', 'item_condition', 'item_completeness']);
        });
    }
};
