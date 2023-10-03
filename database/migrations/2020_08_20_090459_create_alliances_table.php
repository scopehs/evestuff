<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAlliancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('alliances', function (Blueprint $table) {
            $table->id('id');
            $table->string('name', )->nullable();
            $table->string('ticker', )->nullable();
            $table->float('standing')->nullable()->default(0.0);
            $table->boolean('active')->nullable();
            $table->string('url', )->nullable();
            $table->integer('color')->nullable()->default(1);
            $table->timestamps();

            $table->index('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('alliances');
    }
}
