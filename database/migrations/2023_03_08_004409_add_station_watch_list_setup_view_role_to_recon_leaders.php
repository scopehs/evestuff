<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Artisan;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Artisan::call('permission:create-role "Recon Leader" web "view_station_watch_list_setup"');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
    }
};
