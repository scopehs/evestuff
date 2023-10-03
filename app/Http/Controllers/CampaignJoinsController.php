<?php

namespace App\Http\Controllers;

use App\Models\CampaignJoin;
use App\Models\CampaignRecords;
use App\Models\Constellation;
use App\Models\System;
use Illuminate\Http\Request;

class CampaignJoinsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexByID($campid)
    {
        $list = [];
        $pulls = CampaignJoin::where('custom_campaign_id', $campid)->get();
        foreach ($pulls as $pull) {
            $camp = CampaignRecords::where('id', $pull['campaign_id'])->get();
            $count = $camp->count();
            if ($count != 0) {
                foreach ($camp as $camp) {
                    $data = [];
                    $data = [
                        'text' => $camp['system'].' - '.$camp['item_name'],
                        'campaign_id' => $pull['campaign_id'],
                        'custom_campaign_id' => $pull['custom_campaign_id'],
                        'color' => $camp['color'],
                        'status_id' => $camp['status_id'],
                        'constellation_id' => $camp['constellation_id'],
                        'warmup' => $camp['warmup'],
                    ];
                }
                array_push($list, $data);
            }
        }

        return ['value' => $list];
    }

    public function index()
    {
        $list = [];
        $pulls = CampaignJoin::all();
        foreach ($pulls as $pull) {
            $camp = CampaignRecords::where('id', $pull['campaign_id'])->get();
            $count = $camp->count();
            if ($count != 0) {
                foreach ($camp as $camp) {
                    $data = [];
                    $data = [
                        'text' => $camp['system'].' - '.$camp['item_name'],
                        'campaign_id' => $pull['campaign_id'],
                        'custom_campaign_id' => $pull['custom_campaign_id'],
                        'color' => $camp['color'],
                        'status_id' => $camp['status_id'],
                        'constellation_id' => $camp['constellation_id'],
                        'warmup' => $camp['warmup'],
                    ];
                }
                array_push($list, $data);
            }
        }

        return ['value' => $list];
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    public function campaignSystems($id)
    {
        $list = [];
        $pull = CampaignJoin::where('custom_campaign_id', $id)->with('campaign')->get();
        foreach ($pull as $pull) {
            $const = Constellation::where('id', $pull['campaign']['constellation_id'])->get();
            foreach ($const as $const) {
                $sys = System::where('constellation_id', $const['id'])->get();
                foreach ($sys as $sys) {
                    $data = [];
                    $data = [
                        'id' => $sys['id'],
                        'system_name' => $sys['system_name'],
                        'constellation_id' => $sys['constellation_id'],
                        'constellation_name' => $const['constellation_name'],
                    ];
                    array_push($list, $data);
                }
            }
        }
        $list = array_unique($list, SORT_REGULAR);

        return ['systems' => $list];
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $list = [];
        $pull = CampaignJoin::where('custom_campaign_id', $id)->get()->pluck('campaign_id');
        foreach ($pull as $pull) {
            $camp = CampaignRecords::where('id', $pull)->get();
            $count = $camp->count();
            if ($count != 0) {
                foreach ($camp as $camp) {
                    $data = [];
                    $data = [
                        'text' => $camp['system'].' - '.$camp['item_name'],
                    ];
                }
                array_push($list, $data);
            }
        }

        return ['value' => $list];
    }

    public function list($id)
    {
        $pull = CampaignJoin::where('custom_campaign_id', $id)->get()->pluck('campaign_id');

        return ['value' => $pull];
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
