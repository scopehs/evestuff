<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTextColumnToTowersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('towers', function (Blueprint $table) {
            $table->string('text')->after('timestamp')->default(null)->nullable();
            $table->text('notes')->after('text')->default(null)->nullable();
            $table->dateTime('out_time')->after('notes')->default(null)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('towers', function (Blueprint $table) {
            //
        });
    }
}
