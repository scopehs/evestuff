<?php

use Illuminate\Database\Migrations\Migration;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Artisan::call('permission:create-role "Recon Leader" web "view_affiliation_page"');
        Artisan::call('permission:create-role "Coord" web "view_affiliation_page"');
        Artisan::call('permission:create-role "Skyteam" web "view_affiliation_page"');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
