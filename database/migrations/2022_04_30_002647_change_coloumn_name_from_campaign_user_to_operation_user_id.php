<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeColoumnNameFromCampaignUserToOperationUserId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('operation_users', function (Blueprint $table) {
            $table->string('ship')->nullable()->change();
            $table->smallInteger('entosis')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('operation_users', function (Blueprint $table) {
            $table->string('ship')->change();
            $table->smallInteger('entosis')->change();
        });
    }
}
