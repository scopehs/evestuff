<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeOperationUserColoumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('new_node_campaign_users', function (Blueprint $table) {
            $table->renameColumn('campaign_user', 'operation_user_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('new_node_campaign_users', function (Blueprint $table) {
            $table->renameColumn('operation_user_id', 'campaign_user');
        });
    }
}
