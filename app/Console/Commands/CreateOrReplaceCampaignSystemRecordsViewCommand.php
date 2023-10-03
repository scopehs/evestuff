<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CreateOrReplaceCampaignSystemRecordsViewCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'view:CreateOrReplaceCampaignSystemRecordsView';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Will make the view table for Campaign Systems';

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
        DB::statement("CREATE OR REPLACE VIEW campaign_system_records AS SELECT campaign_systems.id AS id,
       campaign_systems.node_id AS node,
       campaign_systems.campaign_id AS campaign_id,
       campaign_systems.system_id AS system_id,
       campaign_systems.custom_campaign_id AS custom_campaign_id,
       systems.system_name AS system_name,
       campaign_systems.campaign_user_id AS user_id,
       campaign_users.site_id AS site_id,
       users.name AS main_name,
       campaign_users.char_name AS user_name,
       campaign_users.ship AS user_ship,
       campaign_users.link AS user_link,
       campaign_systems.campaign_system_status_id AS status_id,
       campaign_system_statuses.name AS status_name,
       campaign_systems.notes AS notes,
       campaign_systems.attack_notes AS attack_notes,
       campaign_systems.attack_adash_link AS attack_adash_link,
       if(((campaign_systems.attack_notes IS NULL) AND (campaign_systems.attack_adash_link IS NULL)),0,1) AS under_attack,
       campaign_systems.created_at AS 'start',
       campaign_systems.end_time AS 'end',
       campaigns.warmup AS 'warmup',
       CONCAT(campaign_records.system, ' - ',campaign_records.item_name) AS 'text',
       campaign_systems.node_join_count AS 'node_join_count'
       FROM campaign_systems
       JOIN systems ON systems.id = campaign_systems.system_id
       JOIN campaign_system_statuses ON campaign_system_statuses.id = campaign_systems.campaign_system_status_id
       LEFT JOIN campaign_users ON campaign_users.id = campaign_systems.campaign_user_id
       LEFT JOIN users ON users.id = campaign_users.site_id
       JOIN campaign_records ON campaign_records.id = campaign_systems.campaign_id
       JOIN campaigns ON campaigns.id = campaign_systems.campaign_id
       WHERE campaign_systems.campaign_system_status_id != 10");
    }
}
