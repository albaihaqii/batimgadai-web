<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('perpanjangan', function (Blueprint $table) {
            $table->string('order_id')->nullable()->after('no_sbg');
            $table->string('transaction_id')->nullable()->after('order_id');
        });

        Schema::table('pelunasan', function (Blueprint $table) {
            $table->string('order_id')->nullable()->after('no_sbg');
            $table->string('transaction_id')->nullable()->after('order_id');
        });
    }
};
