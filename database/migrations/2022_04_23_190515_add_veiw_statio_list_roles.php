<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Artisan;

class AddVeiwStatioListRoles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Artisan::call('permission:create-role "Coord" web "view_station_list"');
        Artisan::call('permission:create-role "Director" web "view_station_list"');
        Artisan::call('permission:create-role "FC" web "view_station_list"');
        Artisan::call('permission:create-role "GSOL" web "view_station_list"');
        Artisan::call('permission:create-role "GSOL Leader" web "view_station_list"');
        Artisan::call('permission:create-role "Ops" web "view_station_list"');
        Artisan::call('permission:create-role "Recon" web "view_station_list"');
        Artisan::call('permission:create-role "Recon Leader" web "view_station_list"');
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
