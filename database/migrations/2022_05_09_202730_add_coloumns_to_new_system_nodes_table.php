<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColoumnsToNewSystemNodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('new_system_nodes', function (Blueprint $table) {
            $table->dateTime('end_time')->nullable();
            $table->dateTime('input_time')->nullable();
            $table->integer('base_time')->nullable();
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
            $table->dropColumn('end_time');
            $table->dropColumn('input_time');
            $table->dropColumn('base_time');
        });
    }
}
