<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNewNodeCampaignUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('new_node_campaign_users', function (Blueprint $table) {
            $table->id();
            $table->foreignId('campaign_user');
            $table->foreignId('system_node_id');
            $table->boolean('prim');
            $table->tinyInteger('status');
            $table->dateTime('timer');
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
        Schema::dropIfExists('new_node_campaign_users');
    }
}
