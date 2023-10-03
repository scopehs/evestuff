<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCampaignsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('campaigns', function (Blueprint $table) {
            $table->id('id');
            $table->float('attackers_score');
            $table->foreignId('constellation_id');
            $table->foreignId('alliance_id');
            $table->float('defenders_score');
            $table->string('event_type');
            $table->foreignId('system_id');
            $table->dateTime('start_time');
            $table->foreignId('structure_id');
            $table->integer('status_id')->default(1);
            $table->integer('check')->nullable();
            $table->dateTime('end')->nullable();
            $table->float('defenders_score_old')->nullable();
            $table->float('attackers_score_old')->nullable();

            $table->index('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('campaigns');
    }
}
