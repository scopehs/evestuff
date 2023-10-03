<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CreateOrReplaceWelpStationRecordsViewCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'view:CreateOrReplaceWelpStationRecordsView';

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
        DB::statement("CREATE OR REPLACE VIEW welp_station_records AS SELECT stations.id AS id,
stations.name AS name,
stations.system_id AS system_id,
systems.system_name AS system_name,
regions.region_name AS region_name,
regions.id AS region_id,
systems.constellation_id AS constellation_id,
constellations.constellation_name AS constellation_name,
stations.alliance_id AS alliance_id,
alliances.name AS alliance_name,
alliances.ticker AS alliance_ticker,
alliances.url AS 'url',
corps.ticker AS 'corp_ticker',
stations.item_id AS item_id,
items.item_name AS item_name,
stations.notes AS notes,
stations.station_status_id AS status_id,
station_statuses.name AS status_name,
stations.show_on_welp AS show_on_welp,
stations.out_time AS end_time,
stations.rc_fc_id AS rc_fc_id,
fc.user_id AS fc_user_id,
fcuser.name AS fc_name,
stations.r_fitted AS fitted,
stations.rc_recon_id AS rc_recon_id,
recon.user_id AS recon_user_id,
reconuser.name AS recon_name,
stations.rc_gsol_id AS gsol_user_id,
gsoluser.name AS gsol_name,
if(stations.out_time> UTC_TIMESTAMP,false,true) AS 'out'
FROM stations
LEFT JOIN corps ON corps.id = stations.corp_id
LEFT JOIN systems ON systems.id = stations.system_id
LEFT JOIN regions ON regions.id = systems.region_id
LEFT JOIN alliances ON alliances.id = corps.alliance_id
LEFT JOIN constellations on constellations.id = systems.constellation_id
LEFT JOIN items ON items.id = stations.item_id
LEFT JOIN station_statuses ON station_statuses.id = stations.station_status_id
LEFT JOIN rc_fc_users AS fc ON fc.id = stations.rc_fc_id
LEFT JOIN rc_recon_users AS recon ON recon.id = stations.rc_recon_id
LEFT JOIN users AS reconuser on reconuser.id = recon.user_id
LEFT JOIN users AS fcuser on fcuser.id = fc.user_id
LEFT JOIN users AS gsoluser on gsoluser.id = stations.rc_gsol_id
WHERE stations.show_on_welp = 1");
    }
}
