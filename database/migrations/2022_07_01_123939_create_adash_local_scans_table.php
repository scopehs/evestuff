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
        Schema::create('adash_local_scans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('system_id');
            $table->string('hash');
            $table->integer('total');
            $table->integer('unaffiliated')->nullable();
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
        Schema::dropIfExists('adash_local_scans');
    }
};
