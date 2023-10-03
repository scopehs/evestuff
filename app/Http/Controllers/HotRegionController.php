<?php

namespace App\Http\Controllers;

use App\Events\StationSheetUpdate;
use App\Events\StationWatchListSettingPageUpdate;
use App\Jobs\ReconRegionPullJob;
use App\Jobs\updateWebwayJob;
use App\Models\Campaign;
use App\Models\HotRegion;
use App\Models\Region;
use App\Models\Station;
use App\Models\System;
use App\Models\WebWay;
use App\Models\WebWayStartSystem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HotRegionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $webwayStart = WebWayStartSystem::where('system_id', '!=', 30004759)->pluck('system_id');
        $webway = System::whereIn('id', $webwayStart)->orderBy('system_name', 'asc')->select(['system_name as text', 'id as value'])->get();

        return [
            'webwayStartSystems' => $webway
        ];
    }

    public function stationWatchListNeededInfo()
    {
        return getGtationWatchListNeededInfo();
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

    public function updateSetting(Request $request)
    {
        // $variables = json_decode(base64_decode(getenv('PLATFORM_VARIABLES')), true);
        // $ids = [];
        // $fcIds = [];
        // $fc = $request->fc;
        // $pull = $request->pull;
        // foreach ($pull as $pull) {
        //     array_push($ids, $pull['value']);
        // }

        // foreach ($fc as $fc) {
        //     array_push($fcIds, $fc['value']);
        // }

        // $h = HotRegion::whereNotNull('id')->get();
        // activity()->disableLogging();
        // foreach ($h as $h) {
        //     $h->update(['update' => 0, 'show_fcs' => 0]);
        // }
        // activity()->enableLogging();
        // $h = HotRegion::whereIn('region_id', $ids)->get();
        // foreach ($h as $h) {
        //     $h->update = 1;
        //     $h->save();
        // }
        // $h = HotRegion::whereIn('region_id', $fcIds)->get();
        // foreach ($h as $h) {
        //     $h->show_fcs = 1;
        //     $h->save();
        // }

        // $flag = collect([
        //     'flag' => 1,
        // ]);
        // broadcast(new StationSheetUpdate($flag));

        // $ids = HotRegion::where('update', 1)->pluck('region_id');

        // foreach ($ids as $id) {
        //     $stations = reconRegionPull($id);
        //     foreach ($stations as $station) {
        //         reconRegionPullIdCheck($station);
        //     }
        // }

        // $w = WebWay::where('id', '>', 0)->get();
        // foreach ($w as $w) {
        //     $w->update(['active' => 0]);
        // }
        // $stations = Station::get();
        // $stationSystems = $stations->pluck('system_id');
        // $campaigns = Campaign::get();
        // $campaginSystems = $campaigns->pluck('system_id');

        // $systemIDs = $stationSystems->merge($campaginSystems);
        // $systemIDs = $systemIDs->unique();
        // $systemIDs = $systemIDs->values();
        // $w = WebWay::whereIn('system_id', $systemIDs)->get();
        // foreach ($w as $w) {
        //     $w->update(['active' => 1]);
        // }
        // $w = WebWay::where('active', 0)->get();
        // foreach ($w as $w) {
        //     $w->delete();
        // }

        // $start_system_id = env('HOME_SYSTEM_ID', ($variables && array_key_exists('HOME_SYSTEM_ID', $variables)) ? $variables['HOME_SYSTEM_ID'] : null);

        // foreach ($systemIDs as $end_system_id) {
        //     updateWebwayJob::dispatch($start_system_id, $end_system_id)->onQueue('webway');
        // }

        // $start_system_ids = WebWayStartSystem::where('system_id', '!=', 30004759)->pluck('system_id');
        // foreach ($start_system_ids as $start_system_id) {
        //     foreach ($systemIDs as $end_system_id) {
        //         updateWebwayJob::dispatch($start_system_id, $end_system_id)->onQueue('webway');
        //     }
        // }

        // $flag = collect([
        //     'flag' => 2,
        // ]);
        // broadcast(new StationSheetUpdate($flag));
    }


    public function stationWatchListRegionUpdate(Request $request)
    {
        $user = Auth::user();
        if ($user->can('view_station_watch_list_setup')) {
            $ids = [];
            $pull = $request->pull;
            foreach ($pull as $pull) {
                array_push($ids, $pull['value']);
            }

            $h = HotRegion::whereIn('region_id', $ids)->get();
            foreach ($h as $h) {
                $h->update = 1;
                $h->save();
            }
            $h = HotRegion::whereNotIn('region_id', $ids)->whereUpdate(1)->get();
            foreach ($h as $h) {
                $h->update = 0;
                $h->save();
            }

            $ids = HotRegion::where('update', 1)->pluck('region_id');
            foreach ($ids as $id) {
                $stations = reconRegionPull($id);

                foreach ($stations as $station) {
                    ReconRegionPullJob::dispatch($station);
                }
            }



            $data = $this->stationWatchListNeededInfo();
            $flag = collect([
                'flag' => 1,
                'message' => $data,
            ]);
            broadcast(new StationWatchListSettingPageUpdate($flag));
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
        $user = Auth::user();
        if ($user->can('edit_hot_region')) {
            $h = HotRegion::where('id', $id)->get();
            foreach ($h as $h) {
                $h->update($request->all());
            }
        }
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
