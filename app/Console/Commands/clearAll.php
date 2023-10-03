<?php

namespace App\Console\Commands;

use App\Models\NewCampaign;
use App\Models\NewCampaignOperation;
use App\Models\NewCampaignSystem;
use App\Models\NewOperation;
use App\Models\NewSystemNode;
use App\Models\NewUserNode;
use App\Models\OperationUser;
use Illuminate\Console\Command;

class clearAll extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'clear:all';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'clear all campaing data';

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
        NewCampaignOperation::truncate();
        NewCampaignSystem::truncate();
        NewCampaign::truncate();
        NewOperation::truncate();
        NewSystemNode::truncate();
        NewUserNode::truncate();
        $a = OperationUser::whereNotNull('id')->get();
        foreach ($a as $a) {
            $a->update([
                'operation_id' => null,
                'user_status_id' => 1,
                'system_id' => null,
            ]);
        }
    }
}
