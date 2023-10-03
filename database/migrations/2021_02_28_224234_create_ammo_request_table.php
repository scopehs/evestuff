<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAmmoRequestTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ammo_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('station_id');
            $table->foreignId('user_id')->nullable();
            $table->text('current_ammo')->nullable();
            $table->text('request_text')->nullable();
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
        Schema::dropIfExists('ammo_request');
    }
}
