<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Artisan::call('permission:create-role "Recon Leader" web "view_staging_page"');
        Artisan::call('permission:create-role "Recon" web "view_staging_page"');
        Artisan::call('permission:create-role "Coord" web "view_staging_page"');
        Artisan::call('permission:create-role "Skyteam" web "view_staging_page"');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
