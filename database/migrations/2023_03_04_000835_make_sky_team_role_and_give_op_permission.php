<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Artisan;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Artisan::call('permission:create-role "Skyteam" web "view_opertaion_info"');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
