<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCampaignSolaSystemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('campaign_sola_systems', function (Blueprint $table) {
            $table->id();
            $table->foreignId('system_id');
            $table->foreignId('campaign_id');
            $table->foreignId('supervisor_id')->nullable()->references('id')->on('users')->onUpdate('no action')
                ->onDelete('no action');
            $table->foreignId('last_checked_user_id')->nullable()->references('id')->on('users')->onUpdate('no action')
                ->onDelete('no action');
            $table->dateTime('last_checked')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('campaign_sola_systems');
    }
}
