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
            $table->renameColumn('distance', 'distance_value');

            $table->string('distance_unit')->nullable();
        });
        Schema::table('dscan_items', function (Blueprint $table) {
            $table->integer('distance_value')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
