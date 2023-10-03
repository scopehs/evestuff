<?php

use Illuminate\Database\Migrations\Migration;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Artisan::call('permission:create-role "Recon" web "view_dscan"');
        Artisan::call('permission:create-role "Recon Leader" web "view_dscan"');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
