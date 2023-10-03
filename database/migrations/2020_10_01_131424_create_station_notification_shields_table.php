<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStationNotificationShieldsTable extends Migration
{
    /**
     * Run the migra  tions.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('station_notification_shields', function (Blueprint $table) {
            $table->id();
            $table->foreignId('station_id')->index();
            $table->dateTime('timestamp');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('station_notification_shields');
    }
}
