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
        Schema::create('operation_info_fleets', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('operation_info_id');
            $table->boolean('gsf_fleet')->default(1);
            $table->foreignId('fc_id')->nullable();
            $table->foreignId('mumble_id')->nullable();
            $table->foreignId('doctrine_id')->nullable();
            $table->foreignId('alliance_id')->nullable();
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
        Schema::dropIfExists('operaton_info_fleets');
    }
};
