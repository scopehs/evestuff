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

class clearOperationCommond extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'clear:operation';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $o = OperationUser::whereNotNull('id')->get();

        foreach ($o as $o) {
            $o->update([
                'operation_id' => null,
                'user_status_id' => 1,
                'system_id' => null,
            ]);
        }
    }
}
