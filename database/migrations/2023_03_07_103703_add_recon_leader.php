<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Artisan;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {

        Artisan::call('permission:create-role "Recon Leader" web "access_campaigns"');
        Artisan::call('permission:create-role "Recon Leader" web "access_multi_campaigns"');
        Artisan::call('permission:create-role "Recon Leader" web "add_nodes"');
        Artisan::call('permission:create-role "Recon Leader" web "campaigns_admin_access"');
        Artisan::call('permission:create-role "Recon Leader" web "edit_killsheet"');
        Artisan::call('permission:create-role "Recon Leader" web "edit_hot_region"');
        Artisan::call('permission:create-role "Recon Leader" web "edit_killsheet_done"');
        Artisan::call('permission:create-role "Recon Leader" web "edit_killsheet_remove_char"');
        Artisan::call('permission:create-role "Recon Leader" web "edit_killsheet_remove_corp"');
        Artisan::call('permission:create-role "Recon Leader" web "edit_notifications"');
        Artisan::call('permission:create-role "Recon Leader" web "edit_opertaion_info"');
        Artisan::call('permission:create-role "Recon Leader" web "edit_recon_task"');
        Artisan::call('permission:create-role "Recon Leader" web "edit_recon_users"');
        Artisan::call('permission:create-role "Recon Leader" web "edit_scout_users"');
        Artisan::call('permission:create-role "Recon Leader" web "edit_station_notifications"');
        Artisan::call('permission:create-role "Recon Leader" web "edit_system_scout"');
        Artisan::call('permission:create-role "Recon Leader" web "edit_users"');
        Artisan::call('permission:create-role "Recon Leader" web "gunner"');
        Artisan::call('permission:create-role "Recon Leader" web "request_recon_task"');
        Artisan::call('permission:create-role "Recon Leader" web "view_campaign_logs"');
        Artisan::call('permission:create-role "Recon Leader" web "view_campaign_members"');
        Artisan::call('permission:create-role "Recon Leader" web "view_coord_sheet"');
        Artisan::call('permission:create-role "Recon Leader" web "view_gsol_killsheet"');
        Artisan::call('permission:create-role "Recon Leader" web "view_killsheet"');
        Artisan::call('permission:create-role "Recon Leader" web "view_opertaion_info"');
        Artisan::call('permission:create-role "Recon Leader" web "view_recon"');
        Artisan::call('permission:create-role "Recon Leader" web "view_station_info_killsheet"');
        Artisan::call('permission:create-role "Recon Leader" web "view_station_list"');
        Artisan::call('permission:create-role "Recon Leader" web "view_station_notifications"');
        Artisan::call('permission:create-role "Recon Leader" web "view_towers"');
        Artisan::call('permission:create-role "Recon Leader" web "view_towers_all"');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
