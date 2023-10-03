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
        Schema::table('dscan_histories', function (Blueprint $table) {
            $table->integer('affiliationsTotalNumber')->nullable()->after('affiliationsTotal');
            $table->integer('alliancesTotalNumber')->nullable()->after('alliancesTotal');
            $table->integer('corpsTotalNumber')->nullable()->after('corpsTotal');
            $table->integer('categoryTotalsNumber')->nullable()->after('categoryTotals');
            $table->integer('groupTotalsNumber')->nullable()->after('groupTotals');
            $table->integer('itemTotalsNumber')->nullable()->after('itemTotals');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dscan_histories', function (Blueprint $table) {
            $table->dropColumn('affiliationsTotalNumber');
            $table->dropColumn('alliancesTotalNumber');
            $table->dropColumn('corpsTotalNumber');
            $table->dropColumn('categoryTotalsNumber');
            $table->dropColumn('groupTotalsNumber');
            $table->dropColumn('itemTotalsNumber');
        });
    }
};
