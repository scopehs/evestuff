<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::dropIfExists('recon_role');
        Artisan::call('permission:create-role "Recon" web "view_move_timers"');
        Artisan::call('permission:create-role "Recon Leader" web "view_move_timers"');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
    }
};
