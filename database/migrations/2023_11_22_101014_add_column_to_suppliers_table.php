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
        Schema::table('suppliers', function (Blueprint $table) {
            $table->float('persen_profit')->after('id')->nullable();
        });

        Schema::table('transaksis', function (Blueprint $table) {
            $table->bigInteger('total_ppn')->after('total_bayar');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('suppliers', function (Blueprint $table) {
            $table->dropColumn('persen_profit');
        });

        Schema::table('transaksis', function (Blueprint $table) {
            $table->dropColumn('total_ppn');
        });
    }
};
