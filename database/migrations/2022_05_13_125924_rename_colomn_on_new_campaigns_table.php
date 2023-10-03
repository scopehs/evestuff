<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameColomnOnNewCampaignsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('new_campaigns', function (Blueprint $table) {
            $table->renameColumn('attacker_node', 'b_node');
            $table->renameColumn('defender_node', 'r_node');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('new_campaigns', function (Blueprint $table) {
            $table->renameColumn('b_node', 'attacker_node');
            $table->renameColumn('r_node', 'defender_node');
        });
    }
}
