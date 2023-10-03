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
        Schema::create('operation_info_recons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->foreignId('operation_info_id')->nullable();
            $table->foreignId('operation_info_fleet_id')->nullable();
            $table->foreignId('system_id')->nullable();
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
        Schema::dropIfExists('operation_info_recons');
    }
};
