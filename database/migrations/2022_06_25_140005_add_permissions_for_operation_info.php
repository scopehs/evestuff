<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Artisan;

return new class () extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Artisan::call("permission:create-role 'Coord' web 'edit_opertaion_info | view_opertaion_info'");
        Artisan::call("permission:create-role 'Director' web 'edit_opertaion_info | view_opertaion_info'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
};
