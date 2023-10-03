<?php

namespace App\Http\Controllers;

use App\Events\CampaignChanged;
use App\Events\CampaignSystemUpdate;

class CampaignController extends Controller
{
    // public function getCampaigns()
    // {
    //     $status = checkeve();
    //     if ($status == 1) {
    //         $flag = update();
    //         if ($flag == 1) {
    //             broadcast(new CampaignChanged($flag))->toOthers();   dsd
    //         }
    //     }
    // }

    public function getCampaigns()
    {
        $status = checkeve();
        if ($status == 1) {
            $request = campaignUpdate();
            $flag = $request[0];
            if ($flag == 1) {
                broadcast(new CampaignChanged($flag))->toOthers();
            }
            $flag = null;
            $check = $request[1];
            foreach ($check as $check) {
                removeNode($check);
                $flag = collect([
                    'flag' => 4,
                    'id' => $check,
                ]);
                broadcast(new CampaignSystemUpdate($flag))->toOthers();
                // }
            }
        }
    }

    public function test()
    {
        $check = 83871;
        removeNode($check);
    }
}
