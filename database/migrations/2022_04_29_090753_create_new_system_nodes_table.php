<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNewSystemNodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('new_system_nodes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('system_id');
            $table->foreignId('campaign_id');
            $table->dateTime('timer')->nullable();
            $table->tinyInteger('node_status')->default(1);
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
        Schema::dropIfExists('new_system_nodes');
    }
}
