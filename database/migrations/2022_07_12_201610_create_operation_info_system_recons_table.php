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
        Schema::create('operation_info_system_recons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('operation_info_system_id');
            $table->foreignId('operation_info_recon_id');
            $table->foreignId('operation_info_recon_status_id')->nullable();
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
        Schema::dropIfExists('operation_info_system_recons');
    }
};
