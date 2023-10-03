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
        Schema::table('dscan_items', function (Blueprint $table) {
            $table->boolean('new')->default(false);
            $table->boolean('same')->default(false);
            $table->boolean('left')->default(false);
            $table->boolean('on_grid')->default(false);
            $table->boolean('updated')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dscan_items', function (Blueprint $table) {
            $table->dropColumn('new');
            $table->dropColumn('same');
            $table->dropColumn('left');
            $table->dropColumn('on_grid');
        });
    }
};
