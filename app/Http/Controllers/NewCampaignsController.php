<?php

namespace App\Http\Controllers;

use App\Events\SoloOperationUpdate;
use App\Models\NewCampaign;
use App\Models\NewOperation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\Models\Activity;

class NewCampaignsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    public function campaignsList()
    {
        $data = [];
        $pull = NewCampaign::whereIn('status_id', [1, 2, 5])->with(['system.region', 'system.constellation', 'alliance'])->orderBy('start_time', 'asc')->get();
        foreach ($pull as $pull) {
            $data1 = [];
            if ($pull->event_type == 32226) {
                $eventType = 'TCU';
            } else {
                $eventType = 'Ihub';
            }
            $systemName = $pull->system->system_name;
            $regionName = $pull->system->region->region_name;
            $constellationName = $pull->system->constellation->constellation_name;
            $allianceName = $pull->alliance->name ?? null;
            $standing = $pull->alliance->standing ?? null;

            $text = $regionName . ' - ' . $constellationName . ' - ' . $systemName . ' - ' . $allianceName . ' - ' . $eventType . ' - ' . $pull->start_time;

            $data1 = [
                'text' => $text,
                'value' => $pull['id'],
                'standing' => $standing
            ];

            array_push($data, $data1);
        }

        // dd($data);

        return ['campaignslist' => $data];
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }


    public function logs($opID)
    {
        $user = Auth::user();
        if ($user->can("view_campaign_members")) {
            $operation = NewOperation::where('id', $opID)->first();
            $campaigns = $operation->campaign()->get();
            $campaignIDs = $campaigns->pluck('id');
            $systemNode = Activity::where('log_name', 'System Node')
                ->where(function ($q) use ($campaignIDs) {
                    $q->whereIn('properties->attributes->campaign_id', $campaignIDs)
                        ->orWHereIn('properties->old->campaign_id', $campaignIDs);
                })
                ->with(['subject', 'causer:id,name'])->get();
            $userNode =
                Activity::where('log_name', 'User Node')
                ->where(function ($q) use ($campaignIDs) {
                    $q->whereIn('properties->attributes->node->campaign_id', $campaignIDs)
                        ->orWHereIn('properties->old->node->campaign_id', $campaignIDs);
                })

                ->with(['subject', 'causer'])->get();

            $readOnly = Activity::where('description', 'Read Only Changed')
                ->where('properties->attributes->id', $opID)
                ->with(['subject', 'causer'])
                ->get();

            $logs = $systemNode->merge($userNode);
            $logs = $logs->merge($readOnly);
            return ['logs' => $logs];
        } else {
            return null;
        }
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
        $n = NewCampaign::find($id)->get();
        foreach ($n as $n) {
            $n->update($request->all());
        }
        $flag = collect([
            'flag' => 4,
            'id' => $id,
        ]);
        broadcast(new SoloOperationUpdate($flag));
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
