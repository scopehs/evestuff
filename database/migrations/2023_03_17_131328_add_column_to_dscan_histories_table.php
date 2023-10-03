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
            $table->json('itemTotals')->nullable();
            $table->json('groupTotals')->nullable();
            $table->json('categoryTotals')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dscan_histories', function (Blueprint $table) {
            $table->dropColumn('itemTotals');
            $table->dropColumn('groupTotals');
            $table->dropColumn('categoryTotals');
        });
    }
};
