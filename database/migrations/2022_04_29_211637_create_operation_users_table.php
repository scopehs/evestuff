<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOperationUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('operation_users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('ship');
            $table->tinyInteger('entosis');
            $table->foreignId('user_id');
            $table->foreignId('operation_id')->nullable();
            $table->foreignId('user_status_id')->nullable();
            $table->foreignId('system_id')->nullable();
            $table->foreignId('role_id')->nullable();
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
        Schema::dropIfExists('operation_users');
    }
}
