<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTempNotifcationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('temp_notifcations', function (Blueprint $table) {
            $table->id();
            $table->integer('event_type_id');
            $table->foreignId('system_id');
            $table->foreignId('notification_type_id');
            $table->dateTime('timestamp');
            $table->bigInteger('es_id');
            $table->integer('status')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('temp_notifcations');
    }
}
