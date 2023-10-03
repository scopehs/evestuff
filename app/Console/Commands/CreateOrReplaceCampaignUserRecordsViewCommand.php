<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CreateOrReplaceCampaignUserRecordsViewCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'view:CreateOrReplaceCampaignUserRecordsView';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Will make the view table for Campaign User';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        DB::statement("CREATE OR REPLACE VIEW campaign_user_records AS SELECT campaign_users.id AS 'id',
        campaign_users.site_id AS 'site_id',
        users.name AS 'main_name',
        campaign_users.campaign_id AS 'campaign_id',
        campaign_users.campaign_system_id AS 'campaign_system_id',
        campaign_systems.node_id AS 'node_id',
        campaign_users.char_name AS 'char_name',
        campaign_users.link AS 'link',
        campaign_users.ship AS 'ship',
        campaign_users.system_id AS 'system_id',
        systems.system_name AS 'system_name',
        campaign_users.status_id AS 'status_id',
        campaign_user_statuses.name AS 'user_status_name',
        campaign_users.campaign_role_id AS 'role_id',
        campaign_user_roles.role AS 'role_name'
        FROM campaign_users
        JOIN users ON users.id = campaign_users.site_id
        LEFT JOIN campaign_systems ON campaign_systems.id = campaign_users.campaign_system_id
        LEFT JOIN systems ON systems.id = campaign_users.system_id
        LEFT JOIN campaign_user_statuses ON campaign_user_statuses.id = campaign_users.status_id
        LEFT JOIN campaign_user_roles ON campaign_user_roles.id = campaign_users.campaign_role_id");
    }
}
