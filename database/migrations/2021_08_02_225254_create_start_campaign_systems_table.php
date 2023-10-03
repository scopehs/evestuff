<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStartCampaignSystemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('start_campaign_systems', function (Blueprint $table) {
            $table->id();
            $table->foreignId('start_campaign_id');
            $table->foreignId('system_id');
            $table->foreignId('constellation_id');
            $table->foreignId('campaign_user_id')->nullable();
            $table->foreignId('campaign_system_status_id')->nullable();
            $table->integer('base_time')->nullable();
            $table->dateTime('input_time')->nullable();
            $table->dateTime('end_time')->nullable();
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
        Schema::dropIfExists('start_campaign_systems');
    }
}
