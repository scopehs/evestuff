<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Artisan;

class AddTacticalCommanderRoleAndPermissions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Artisan::call('permission:create-role "TC" web "access_campaigns"');
        Artisan::call('permission:create-role "TC" web "edit_notifications"');
        Artisan::call('permission:create-role "TC" web "view_killsheet"');
        Artisan::call('permission:create-role "TC" web "view_move_timers"');
        Artisan::call('permission:create-role "TC" web "view_station_list"');
        Artisan::call('permission:create-role "TC" web "view_station_info_killsheet"');
        Artisan::call('permission:create-role "FC" web "view_station_info_killsheet"');
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
