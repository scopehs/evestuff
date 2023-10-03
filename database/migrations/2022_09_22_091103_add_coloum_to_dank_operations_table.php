<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('dank_operations', function (Blueprint $table) {
            $table->uuid('dank_id');
            $table->string('name');
            $table->string('link');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('dank_operations', function (Blueprint $table) {
            $table->dropColumn('dank_id');
            $table->dropColumn('name');
            $table->dropColumn('link');
        });
    }
};
