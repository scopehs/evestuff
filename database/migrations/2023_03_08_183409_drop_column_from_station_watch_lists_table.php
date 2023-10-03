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
        Schema::table('station_watch_lists', function (Blueprint $table) {
            $table->dropColumn('stations');
            $table->dropColumn('systems');
            $table->dropColumn('constellations');
            $table->dropColumn('regions');
            $table->dropColumn('roles');
            $table->dropColumn('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('station_watch_lists', function (Blueprint $table) {
            //
        });
    }
};
