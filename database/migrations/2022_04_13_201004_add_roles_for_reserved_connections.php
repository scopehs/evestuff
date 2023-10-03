<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Artisan;

class AddRolesForReservedConnections extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Artisan::call('permission:create-role "Coord" web "use_reserved_connection"');
        Artisan::call('permission:create-role "Director" web "use_reserved_connection"');
        Artisan::call('permission:create-role "GSOL" web "use_reserved_connection"');
        Artisan::call('permission:create-role "Ops" web "use_reserved_connection"');
        Artisan::call('permission:create-role "Recon" web "use_reserved_connection"');
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
