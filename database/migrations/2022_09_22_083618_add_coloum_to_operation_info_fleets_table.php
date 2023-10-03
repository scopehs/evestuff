<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('operation_info_fleets', function (Blueprint $table) {
            $table->dateTime('started')->nullable();
            $table->dateTime('closed')->nullable();
            $table->foreignId('dank_operation_id')->nullable();
            $table->uuid('uid');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('operation_info_fleets', function (Blueprint $table) {
            $table->dropColumn('started');
            $table->dropColumn('closed');
            $table->dropColumn('dank_operation_id');
            $table->dropColumn('uid');
        });
    }
};
