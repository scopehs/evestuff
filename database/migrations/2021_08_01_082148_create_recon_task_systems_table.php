<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReconTaskSystemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('recon_task_systems', function (Blueprint $table) {
            $table->id();
            $table->foreignId('recon_task_id');
            $table->foreignId('system_id');
            $table->foreignId('user_id')->nullable();
            $table->dateTime('last_edit')->nullable();
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
        Schema::dropIfExists('recon_task_systems');
    }
}
