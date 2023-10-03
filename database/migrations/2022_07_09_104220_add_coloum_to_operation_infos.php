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
        Schema::table('operation_infos', function (Blueprint $table) {
            $table->boolean('check_list')->default(1);
            $table->boolean('recon_table')->default(1);
            $table->boolean('system_table')->default(1);
            $table->boolean('fleet_table')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('operation_infos', function (Blueprint $table) {
            $table->dropColumn('check_list');
            $table->dropColumn('recon_table');
            $table->dropColumn('system_table');
            $table->dropColumn('fleet_table');
        });
    }
};
