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
        Schema::table('dscan_locals', function (Blueprint $table) {
            $table->boolean('new')->default(true);
            $table->boolean('old')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dscan_locals', function (Blueprint $table) {
            $table->dropColumn('new');
            $table->dropColumn('old');
        });
    }
};
