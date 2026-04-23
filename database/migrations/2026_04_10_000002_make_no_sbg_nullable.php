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
        // no-op: initial pawn_transactions create already defines no_sbg as nullable.
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // no-op: the schema already matches the intended nullable state on fresh installs.
    }
};
