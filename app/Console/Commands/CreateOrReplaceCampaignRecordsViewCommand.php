<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CreateOrReplaceCampaignRecordsViewCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'view:CreateOrReplaceCampaignRecordsView';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Will make the view table for Campaigns';

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
        DB::statement(
            "CREATE OR REPLACE VIEW campaign_records AS SELECT campaigns.id AS 'id',
        regions.region_name AS 'region',
        regions.id AS 'region_id',
        constellations.constellation_name AS 'constellation',
        campaigns.constellation_id AS 'constellation_id',
        systems.system_name AS 'system',
        campaigns.system_id AS 'system_id',
        alliances.name AS 'alliance',
        campaigns.alliance_id AS 'alliance_id',
        alliances.ticker AS 'ticker',
        alliances.color AS 'color',
        alliances.url AS 'url',
        campaigns.event_type AS 'event_type',
        items.item_name AS 'item_name',
        campaigns.attackers_score AS 'attackers_score',
        campaigns.defenders_score AS 'defenders_score',
        campaigns.status_id AS 'status_id',
        campaign_statuses.name AS 'status_name',
        alliances.standing AS 'standing',
        campaigns.start_time AS 'start',
        campaigns.defenders_score_old AS 'defenders_score_old',
        campaigns.attackers_score_old AS 'attackers_score_old',
        campaigns.b_node AS 'b_node',
        campaigns.r_node AS 'r_node',
        campaigns.b_node + campaigns.r_node AS 'total_node',
        campaigns.warmup AS 'warmup',
        systems.adm AS 'adm',
        campaigns.link AS 'link',
        structures.age AS 'Age'
        FROM campaigns
        JOIN systems ON systems.id = campaigns.system_id
        JOIN constellations ON constellations.id = campaigns.constellation_id
        JOIN alliances ON alliances.id = campaigns.alliance_id
        JOIN items ON items.id = campaigns.event_type
        JOIN regions ON regions.id = systems.region_id
        JOIN campaign_statuses ON campaign_statuses.id = campaigns.status_id
        LEFT JOIN structures on structures.id = campaigns.structure_id
        WHERE (campaigns.attackers_score != 1 OR campaigns.attackers_score != 0) AND campaigns.status_id != 10"
        );
    }
}

// WHERE (campaigns.attackers_score != 1 OR campaigns.attackers_score != 0) AND campaigns.status_id != 10");
