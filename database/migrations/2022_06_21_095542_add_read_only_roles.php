<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Artisan;

class AddReadOnlyRoles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Artisan::call("permission:create-role 'Coord' web 'edit_read_only | view_operation_read_only'");
        Artisan::call("permission:create-role 'Director' web 'edit_read_only | view_operation_read_only'");
        Artisan::call("permission:create-role 'GSFOE Leader' web 'edit_read_only | view_operation_read_only'");
        Artisan::call("permission:create-role 'GSFOE FC' web 'edit_read_only | view_operation_read_only'");
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
}
