<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('dscans', function (Blueprint $table) {
            $table->json('totals')->nullable();
            $table->json('dscan')->nullable();
            $table->json('corpTotal')->nullable();
            $table->json('allianceTotal')->nullable();
            $table->json('affiliationsTotal')->nullable();
            $table->json('itemsTotals')->nullable();
            $table->json('groupTotals')->nullable();
            $table->json('categoryTotals')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dscans', function (Blueprint $table) {
            $table->dropColumn('totals');
            $table->dropColumn('dscan');
            $table->dropColumn('corpTotal');
            $table->dropColumn('allianceTotal');
            $table->dropColumn('affiliationsTotal');
            $table->dropColumn('itemsTotals');
            $table->dropColumn('groupTotals');
            $table->dropColumn('categoryTotals');
        });
    }
};
