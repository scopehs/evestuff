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

            $table->renameColumn('old', 'left');
            $table->boolean('new')->default(false)->change();
            $table->boolean('same')->default(false)->after('new');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dscan_locals', function (Blueprint $table) {
            $table->renameColumn('left', 'old');
            $table->boolean('new')->default(true)->change();
            $table->dropColumn('same');
        });
    }
};
