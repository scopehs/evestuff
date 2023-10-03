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
        Schema::table('operation_infos', function (Blueprint $table) {
            $table->boolean('watched_system_table')->default(true)->after('system_table');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('operation_infos', function (Blueprint $table) {
            $table->dropColumn('watched_system_table');
        });
    }
};
