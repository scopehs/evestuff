<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CreateOrReplaceStartCampaignSystemRecordsViewCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'view:CreateOrReplaceStartCampaignSystemRecordsViewCommand';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        DB::statement('CREATE OR REPLACE VIEW start_campaign_system_records AS SELECT start_campaign_systems.id AS id,
start_campaign_systems.start_campaign_id AS start_campaign_id,
start_campaign_systems.system_id AS system_id,
systems.system_name AS system_name,
systems.constellation_id AS constellation_id,
start_campaign_systems.campaign_user_id AS user_id,
campaign_users.site_id AS site_id,
campaign_users.char_name AS user_name,
campaign_users.ship AS user_ship,
campaign_users.link AS user_link,
start_campaign_systems.campaign_system_status_id AS status_id,
campaign_system_statuses.name AS status_name,
start_campaign_systems.input_time AS start_time,
start_campaign_systems.end_time AS end_time,
users.name AS main_name
FROM start_campaign_systems
LEFT JOIN systems ON systems.id = start_campaign_systems.system_id
LEFT JOIN campaign_users ON campaign_users.id = start_campaign_systems.campaign_user_id
LEFT JOIN campaign_system_statuses ON campaign_system_statuses.id = start_campaign_systems.campaign_system_status_id
LEFT JOIN users ON users.id = campaign_users.site_id');
    }
}
