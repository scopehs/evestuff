<?php

namespace App\Http\Controllers;

use App\Events\StationSheetUpdate;
use App\Events\StationWatchListSettingPageUpdate;
use App\Models\AllianceStationWatchList;
use App\Models\ConstellationStationWatchList;
use App\Models\ItemStationWatchList;
use App\Models\RegionStationWatchList;
use App\Models\RoleStationWatchList;
use App\Models\StationStationWatchList;
use App\Models\StationWatchList;
use App\Models\SystemStationWatchList;
use App\Models\UserStationWatchList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StationWatchListController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return [
            'watchList' => allWatchList()
        ];
    }


    /**
     * Display a list of watch lists a user has.
     */
    public function userWatchLists()
    {
        return [
            'watchList' => getUsersWatchLists()
        ];
    }



    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {



        $new = new StationWatchList();
        $new->created_by = Auth::id();
        $new->created_by = Auth::id();
        $new->updated_by = Auth::id();
        $new->name = $request->name;
        $new->description = $request->description;
        $new->settings = [
            'station_id' => $request->station_id,
            'system_id' => $request->system_id,
            'constellation_id' => $request->constellation_id,
            'region_id' => $request->region_id,
            'role_id' => $request->role_id,
            'user_id' => $request->user_id,
            'alliance_id' => $request->alliance_id,
            'item_id' => $request->item_id,
        ];

        $new->save();

        foreach ($request->station_id as $station) {
            $stationJoin = new StationStationWatchList();
            $stationJoin->station_watch_list_id = $new->id;
            $stationJoin->station_id = $station;
            $stationJoin->save();
        }

        foreach ($request->system_id as $system) {
            $systemJoin = new SystemStationWatchList();
            $systemJoin->station_watch_list_id = $new->id;
            $systemJoin->system_id = $system;
            $systemJoin->save();
        }

        foreach ($request->constellation_id as $constellation) {
            $constellationJoin = new ConstellationStationWatchList();
            $constellationJoin->station_watch_list_id = $new->id;
            $constellationJoin->constellation_id = $constellation;
            $constellationJoin->save();
        }

        foreach ($request->region_id as $region) {
            $regionJoin = new RegionStationWatchList();
            $regionJoin->station_watch_list_id = $new->id;
            $regionJoin->region_id = $region;
            $regionJoin->save();
        }

        foreach ($request->role_id as $role) {
            $roleJoin = new RoleStationWatchList();
            $roleJoin->station_watch_list_id = $new->id;
            $roleJoin->role_id = $role;
            $roleJoin->save();
        }

        foreach ($request->user_id as $user) {
            $userJoin = new UserStationWatchList();
            $userJoin->station_watch_list_id = $new->id;
            $userJoin->user_id = $user;
            $userJoin->save();
        }

        foreach ($request->alliance_id as $alliance) {
            $allianceJoin = new AllianceStationWatchList();
            $allianceJoin->station_watch_list_id = $new->id;
            $allianceJoin->alliance_id = $alliance;
            $allianceJoin->save();
        }

        foreach ($request->type_id as $item) {
            $itemJoin = new ItemStationWatchList();
            $itemJoin->station_watch_list_id = $new->id;
            $itemJoin->item_id = $item;
            $itemJoin->save();
        }

        $message = allWatchListSolo($new->id);
        $flag = collect([
            'flag' => 3,
            'message' => $message,
        ]);
        broadcast(new StationWatchListSettingPageUpdate($flag));

        $flag = collect([
            'flag' => 3,
        ]);

        broadcast(new StationSheetUpdate($flag));
    }

    /**
     * Display the specified resource.
     */
    public function show(StationWatchList $stationWatchList)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $list = StationWatchList::whereId($id)->first();
        $list->created_by = Auth::id();
        $list->created_by = Auth::id();
        $list->updated_by = Auth::id();
        $list->name = $request->name;
        $list->description = $request->description;
        $list->settings = [
            'station_id' => $request->station_id,
            'system_id' => $request->system_id,
            'constellation_id' => $request->constellation_id,
            'region_id' => $request->region_id,
            'role_id' => $request->role_id,
            'user_id' => $request->user_id,
            'alliance_id' => $request->alliance_id,
            'type_id' => $request->type_id,
        ];
        $list->active = $request->active ?? $list->active;
        $list->save();

        StationStationWatchList::whereStationWatchListId($id)->delete();

        foreach ($request->station_id as $station) {
            $stationJoin = new StationStationWatchList();
            $stationJoin->station_watch_list_id = $list->id;
            $stationJoin->station_id = $station;
            $stationJoin->save();
        }

        SystemStationWatchList::whereStationWatchListId($id)
            ->delete();

        foreach ($request->system_id as $system) {
            $systemJoin = new SystemStationWatchList();
            $systemJoin->station_watch_list_id = $list->id;
            $systemJoin->system_id = $system;
            $systemJoin->save();
        }

        ConstellationStationWatchList::whereStationWatchListId($id)
            ->delete();

        foreach ($request->constellation_id as $constellation) {
            $constellationJoin = new ConstellationStationWatchList();
            $constellationJoin->station_watch_list_id = $list->id;
            $constellationJoin->constellation_id = $constellation;
            $constellationJoin->save();
        }

        RegionStationWatchList::whereStationWatchListId($id)
            ->delete();

        foreach ($request->region_id as $region) {
            $regionJoin = new RegionStationWatchList();
            $regionJoin->station_watch_list_id = $list->id;
            $regionJoin->region_id = $region;
            $regionJoin->save();
        }

        RoleStationWatchList::whereStationWatchListId($id)
            ->delete();

        foreach ($request->role_id as $role) {
            $roleJoin = new RoleStationWatchList();
            $roleJoin->station_watch_list_id = $list->id;
            $roleJoin->role_id = $role;
            $roleJoin->save();
        }

        UserStationWatchList::whereStationWatchListId($id)
            ->delete();

        foreach ($request->user_id as $user) {
            $userJoin = new UserStationWatchList();
            $userJoin->station_watch_list_id = $list->id;
            $userJoin->user_id = $user;
            $userJoin->save();
        }

        AllianceStationWatchList::whereStationWatchListId($id)
            ->delete();

        foreach ($request->alliance_id as $alliance) {
            $allianceJoin = new AllianceStationWatchList();
            $allianceJoin->station_watch_list_id = $list->id;
            $allianceJoin->alliance_id = $alliance;
            $allianceJoin->save();
        }

        ItemStationWatchList::whereStationWatchListId($id)
            ->delete();

        foreach ($request->type_id as $item) {
            $itemJoin = new ItemStationWatchList();
            $itemJoin->station_watch_list_id = $list->id;
            $itemJoin->item_id = $item;
            $itemJoin->save();
        }


        $message = allWatchListSolo($id);
        $flag = collect([
            'flag' => 3,
            'message' => $message,
        ]);
        broadcast(new StationWatchListSettingPageUpdate($flag));

        $flag = collect([
            'flag' => 3,
        ]);

        broadcast(new StationSheetUpdate($flag));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        StationStationWatchList::where('station_watch_list_id', $id)->delete();
        SystemStationWatchList::where('station_watch_list_id', $id)->delete();
        ConstellationStationWatchList::where('station_watch_list_id', $id)->delete();
        RegionStationWatchList::where('station_watch_list_id', $id)->delete();
        RoleStationWatchList::where('station_watch_list_id', $id)->delete();
        UserStationWatchList::where('station_watch_list_id', $id)->delete();
        AllianceStationWatchList::where('station_watch_list_id', $id)->delete();
        ItemStationWatchList::where('station_watch_list_id', $id)->delete();
        StationWatchList::where('id', $id)->delete();


        $flag = collect([
            'flag' => 4,
            'id' => $id,
        ]);
        broadcast(new StationWatchListSettingPageUpdate($flag));

        $flag = collect([
            'flag' => 3,
        ]);

        broadcast(new StationSheetUpdate($flag));
    }
}
