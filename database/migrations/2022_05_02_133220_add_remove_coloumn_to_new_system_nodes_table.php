<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRemoveColoumnToNewSystemNodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('new_system_nodes', function (Blueprint $table) {
            $table->dropColumn('timer');
            $table->string('name')->after('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('new_system_nodes', function (Blueprint $table) {
            $table->dropColumn('name');
            $table->dateTime('timer')->nullable();
        });
    }
}
