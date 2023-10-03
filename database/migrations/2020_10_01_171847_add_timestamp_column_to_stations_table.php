<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTimestampColumnToStationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('stations', function (Blueprint $table) {
            // $table->dateTime('timestamp')->after('station_status_id')->nullable();
            $table->tinyInteger('show_on_main')->after('timestamp')->default('0');
            $table->tinyInteger('show_on_chill')->after('show_on_main')->default('0');
            $table->tinyInteger('show_on_rc')->after('show_on_chill')->default('0');
            $table->foreignId('alliance_id')->after('corp_id')->default('0');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('stations', function (Blueprint $table) {
            //
        });
    }
}
