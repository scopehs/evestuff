<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CreateOrReplaceReconTaskSystemRecordsView extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'view:CreateOrReplaceReconTaskSystemRecordsView';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Will make the view table for recon tasks';

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
        DB::statement('CREATE OR REPLACE VIEW recon_task_system_records AS SELECT recon_task_systems.id AS id,
        recon_task_systems.recon_task_id AS recon_task_id,
recon_task_systems.system_id AS system_id,
recon_task_systems.last_edit AS last_edit,
systems.system_name AS system_name,
systems.region_id AS region_id,
systems.constellation_id AS constellation_id,
constellations.constellation_name AS constellation_name,
regions.region_name AS region_name,
users.name AS user_name,
users.id AS user_id,
recon_task_systems.created_at AS created_at,
recon_task_systems.updated_at AS updated_at
FROM recon_task_systems
LEFT JOIN users ON users.id = recon_task_systems.user_id
LEFT JOIN systems ON systems.id = recon_task_systems.system_id
LEFT JOIN regions ON regions.id = systems.region_id
LEFT JOIN constellations ON constellations.id = systems.constellation_id');
    }
}
