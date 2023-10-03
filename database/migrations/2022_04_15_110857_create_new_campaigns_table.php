<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNewCampaignsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('new_campaigns', function (Blueprint $table) {
            $table->id('id');
            $table->uuid('link')->nullable();
            $table->float('attackers_score');
            $table->float('attackers_score_old')->nullable();
            $table->foreignId('constellation_id');
            $table->foreignId('alliance_id');
            $table->float('defenders_score');
            $table->float('defenders_score_old')->nullable();
            $table->string('event_type');
            $table->foreignId('system_id');
            $table->dateTime('start_time');
            $table->foreignId('structure_id');
            $table->integer('status_id')->default(1);
            $table->dateTime('end_time')->nullable();
            $table->integer('attacker_node')->default(0);
            $table->integer('defender_node')->default(0);
            $table->boolean('check')->default(0);
            $table->timestamps();
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
        Schema::dropIfExists('new_campaigns');
    }
}
