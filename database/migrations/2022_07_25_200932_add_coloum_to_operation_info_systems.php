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
        Schema::table('operation_info_systems', function (Blueprint $table) {
            Schema::table('operation_info_systems', function (Blueprint $table) {
                $table->integer('cynos_needed')->default(0);
            });
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('operation_info_systems', function (Blueprint $table) {
            $table->dropColumn('cynos_needed');
        });
    }
};
