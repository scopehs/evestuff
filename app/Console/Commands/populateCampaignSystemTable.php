<?php

namespace App\Console\Commands;

use App\Models\NewCampaign;
use App\Models\NewCampaignSystem;
use App\Models\System;
use Illuminate\Console\Command;

class populateCampaignSystemTable extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:campaignsystems';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'populate campaign systems';

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
        activity()->disableLogging();
        $campaigns = NewCampaign::whereNotNull('id')->get();
        $this->info($campaigns);
        foreach ($campaigns as $c) {
            $systemsIDs = System::where('constellation_id', $c->constellation_id)
                ->pluck('id');
            $this->info($systemsIDs);
            foreach ($systemsIDs as $sid) {
                NewCampaignSystem::create([
                    'system_id' => $sid,
                    'new_campaign_id' => $c->id,
                ]);
            }
        }
        activity()->enableLogging();
    }
}
