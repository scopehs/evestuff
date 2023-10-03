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
        Schema::dropIfExists('dank_fleets');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::create('dank_fleets', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->dateTime('started');
            $table->dateTime('closed')->nullable();
            $table->foreignId('fc_id')->nullable();
            $table->foreignId('boss_id')->nullable();
            $table->foreignId('dank_operation_id');
            $table->timestamps();
        });
    }
};
