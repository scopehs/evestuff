<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Artisan;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Artisan::call('permission:create-role "Recon Leader" web "access_station_sheet_setting_tab"');
        Artisan::call('permission:create-role "Coord" web "access_station_sheet_setting_tab"');
        Artisan::call('permission:create-role "Skyteam" web "access_station_sheet_setting_tab"');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
