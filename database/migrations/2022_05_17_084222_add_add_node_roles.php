<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Artisan;

class AddAddNodeRoles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Artisan::call('permission:create-role "Coord" web "add_nodes"');
        Artisan::call('permission:create-role "Director" web "add_nodes"');
        Artisan::call('permission:create-role "FC" web "add_nodes"');
        Artisan::call('permission:create-role "GSFOE FC" web "add_nodes"');
        Artisan::call('permission:create-role "GSFOE Leader" web "add_nodes"');
        Artisan::call('permission:create-role "GSOL" web "add_nodes"');
        Artisan::call('permission:create-role "GSOL Leader" web "add_nodes"');
        Artisan::call('permission:create-role "Ops" web "add_nodes"');
        Artisan::call('permission:create-role "Recon" web "add_nodes"');
        Artisan::call('permission:create-role "Recon Leader" web "add_nodes"');
        Artisan::call('permission:create-role "TC" web "add_nodes"');
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
