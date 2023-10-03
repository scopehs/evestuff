<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSystemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('systems', function (Blueprint $table) {
            $table->foreignId('region_id');
            $table->foreignId('constellation_id');
            $table->id('id');
            $table->string('system_name');
            $table->float('adm')->default(0.0);
            $table->timestamps();

            $table->index('id');
            $table->index('region_id');
            $table->index('constellation_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('systems');
    }
}
