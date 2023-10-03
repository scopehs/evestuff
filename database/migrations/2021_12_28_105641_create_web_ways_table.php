<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWebWaysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('web_ways', function (Blueprint $table) {
            $table->id();
            $table->foreignId('system_id');
            $table->uuid('webway')->nullable();
            $table->tinyInteger('jumps')->nullable();
            $table->boolean('active')->default(1);
            $table->boolean('permissions')->default(0);
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
        Schema::dropIfExists('web_ways');
    }
}
