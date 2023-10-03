<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCampaignUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('campaign_users', function (Blueprint $table) {
            $table->id();
            $table->integer('site_id');
            $table->foreignId('campaign_id');
            $table->foreignId('campaign_system_id')->nullable();
            $table->string('char_name', 50);
            $table->tinyInteger('link')->nullable();
            $table->string('ship', 50)->nullable();
            $table->foreignId('system_id')->nullable();
            $table->integer('status_id')->nullable();
            $table->integer('campaign_role_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('campaign_users');
    }
}
