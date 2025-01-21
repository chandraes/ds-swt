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
        Schema::table('ppn_keluarans', function (Blueprint $table) {
            $table->foreignId('invoice_ppn_id')->nullable()->after('id')->constrained()->onDelete('SET NULL');
        });

        Schema::table('invoice_ppns', function (Blueprint $table) {
            $table->bigInteger('penyesuaian')->default(0)->after('total_ppn');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ppn_keluarans', function (Blueprint $table) {
            $table->dropForeign(['invoice_ppn_id']);
            $table->dropColumn('invoice_ppn_id');
        });

        Schema::table('invoice_ppns', function (Blueprint $table) {
            $table->dropColumn('penyesuaian');
        });
    }
};
