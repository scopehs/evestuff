<?php

use App\Models\Alliance;
use App\Models\AllianceStationWatchList;
use App\Models\Constellation;
use App\Models\ConstellationStationWatchList;
use App\Models\HotRegion;
use App\Models\Item;
use App\Models\ItemStationWatchList;
use App\Models\Region;
use App\Models\RegionStationWatchList;
use App\Models\RoleStationWatchList;
use App\Models\Station;
use App\Models\StationItemJoin;
use App\Models\StationStationWatchList;
use App\Models\SystemStationWatchList;
use App\Models\StationWatchList;
use App\Models\System;
use App\Models\User;
use App\Models\UserStationWatchList;
use App\Models\WebWayStartSystem;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;

if (!function_exists('allWatchList')) {
    function allWatchList()
    {
        $watchList = StationWatchList::with([
            "stations:id,name",
            "systems:id,system_name",
            "constellations:id,constellation_name",
            "regions:id,region_name",
            "roles:id,name",
            "users:id,name",
            "alliances:id,ticker",
            "items:id,item_name",
        ])
            ->get();

        return $watchList;
    }
}

if (!function_exists('allWatchListSolo')) {

    function allWatchListSolo($id)
    {
        $watchList = StationWatchList::whereId($id)->with([
            "stations:id,name",
            "systems:id,system_name",
            "constellations:id,constellation_name",
            "regions:id,region_name",
            "roles:id,name",
            "users:id,name",
            "alliances:id,ticker",
            "items:id,item_name",
        ])
            ->first();

        return $watchList;
    }
}

if (!function_exists('getAllallowedStations')) {
    function getAllallowedStations()
    {
        $stationIds = collect();
        $user = User::whereId(Auth::id())->first();
        $roleIDs = $user->roles()->pluck('id');

        if (!$roleIDs->contains(2)) {
            $roleList = RoleStationWatchList::whereIn('role_id', $roleIDs)->pluck('station_watch_list_id');
            $userList = UserStationWatchList::where('user_id', Auth::id())->pluck('station_watch_list_id');

            $merge = $roleList->merge($userList);
            $watchlists = StationWatchList::whereIn('id', $merge)->where('active', true)->pluck('id');
        } else {
            $watchlists = StationWatchList::where('active', true)->pluck('id');
        }



        foreach ($watchlists as $watchlist) {
            $station = StationStationWatchList::where('station_watch_list_id', $watchlist)->pluck('station_id');
            $system = SystemStationWatchList::where('station_watch_list_id', $watchlist)->pluck('system_id');
            $constellation = ConstellationStationWatchList::where('station_watch_list_id', $watchlist)->pluck('constellation_id');
            $region = RegionStationWatchList::where('station_watch_list_id', $watchlist)->pluck('region_id');
            $alliance = AllianceStationWatchList::where('station_watch_list_id', $watchlist)->pluck('alliance_id');
            $item = ItemStationWatchList::where('station_watch_list_id', $watchlist)->pluck('item_id');

            $station_query = Station::query();
            $station_query->join('systems', 'stations.system_id', '=', 'systems.id');
            if (count($station)) {
                $station_query->whereIn('stations.id', $station);
            }
            if (count($system)) {
                $station_query->orWhereIn('stations.system_id', $system);
            }
            if (count($constellation)) {
                $station_query->orWhereIn('systems.constellation_id', $constellation);
            }
            if (count($region)) {
                $station_query->orWhereIn('systems.region_id', $region);
            }
            if (count($alliance)) {
                $station_query->whereHas(
                    'alliance',
                    function ($query) use ($alliance) {
                        $query->whereIn('alliances.id', $alliance);
                    }
                );
            }
            if (count($item)) {
                $station_query->whereIn('stations.item_id', $item);
            }


            $stations =
                $station_query->pluck('stations.id')
                ->unique()
                ->values();

            $stationIds = $stationIds->merge($stations);
        }


        $stationIds = $stationIds->unique()->values();

        $data = StationRecords(6, $stationIds);
        return $data;
    }
}


if (!function_exists('getStationWatchListIDs')) {
    function getStationWatchListIDs($stationID)
    {

        $listIDs = collect();
        $watchlists = StationWatchList::where('active', true)->pluck('id');

        foreach ($watchlists as $watchlist) {
            $station = StationStationWatchList::where('station_watch_list_id', $watchlist)->pluck('station_id');
            $system = SystemStationWatchList::where('station_watch_list_id', $watchlist)->pluck('system_id');
            $constellation = ConstellationStationWatchList::where('station_watch_list_id', $watchlist)->pluck('constellation_id');
            $region = RegionStationWatchList::where('station_watch_list_id', $watchlist)->pluck('region_id');
            $alliance = AllianceStationWatchList::where('station_watch_list_id', $watchlist)->pluck('alliance_id');
            $item = ItemStationWatchList::where('station_watch_list_id', $watchlist)->pluck('item_id');

            $station_query = Station::query();
            $station_query->join('systems', 'stations.system_id', '=', 'systems.id');
            if (count($station)) {
                $station_query->whereIn('stations.id', $station);
            }
            if (count($system)) {
                $station_query->orWhereIn('stations.system_id', $system);
            }
            if (count($constellation)) {
                $station_query->orWhereIn('systems.constellation_id', $constellation);
            }
            if (count($region)) {
                $station_query->orWhereIn('systems.region_id', $region);
            }
            if (count($alliance)) {
                $station_query->whereHas(
                    'alliance',
                    function ($query) use ($alliance) {
                        $query->whereIn('alliances.id', $alliance);
                    }
                );
            }
            if (count($item)) {
                $station_query->whereIn('stations.item_id', $item);
            }


            $stations =
                $station_query->pluck('stations.id')
                ->unique()
                ->values();
            if ($stations->contains($stationID)) {
                $listIDs->push($watchlist);
            }
        }

        return $listIDs;
    }
}

if (!function_exists('getUsersWatchLists')) {
    function getUsersWatchLists()
    {
        $user = User::whereId(Auth::id())->first();
        $roleIDs = $user->roles()->pluck('id');

        if (!$roleIDs->contains(2)) {
            $roleList = RoleStationWatchList::whereIn('role_id', $roleIDs)->pluck('station_watch_list_id');
            $userList = UserStationWatchList::where('user_id', Auth::id())->pluck('station_watch_list_id');

            $merge = $roleList->merge($userList);
            $watchlist = StationWatchList::whereIn('id', $merge)->where('active', true)->pluck('id');
        } else {
            $watchlist = StationWatchList::where('active', true)->pluck('id');
        }

        $lists = StationWatchList::whereIn('id', $watchlist)->whereActive(1)->select('id', 'name')->get();

        return $lists;
    }
}

if (!function_exists('getGtationWatchListNeededInfo')) {
    function getGtationWatchListNeededInfo()
    {

        $pullStart = HotRegion::where('update', 1)->pluck('region_id');
        $pull = Region::whereIn('id', $pullStart)
            ->orderBy('region_name', 'asc')
            ->select(['region_name as text', 'id as value'])
            ->get();

        $webwayStart = WebWayStartSystem::where('system_id', '!=', 30004759)
            ->pluck('system_id');
        $webway = System::whereIn('id', $webwayStart)
            ->orderBy('system_name', 'asc')
            ->select(['system_name as text', 'id as value'])
            ->get();

        $regionDropDownList = Region::whereIn('id', $pullStart)
            ->orderBy('region_name', 'asc')
            ->select(['region_name as text', 'id as value'])
            ->get();

        $regionList = Region::whereNotNull('id')
            ->orderBy('region_name', 'asc')
            ->select(['region_name as text', 'id as value'])
            ->get();


        $systemDropDownList = System::whereIn('region_id', $pullStart)
            ->orderBy('system_name', 'asc')
            ->select(['system_name as text', 'id as value'])
            ->get();

        $systemList = System::whereNotNull('id')
            ->orderBy('system_name', 'asc')
            ->select(['system_name as text', 'id as value'])
            ->get();


        $constellationDropDownList = Constellation::whereIn('region_id', $pullStart)
            ->orderBy('constellation_name', 'asc')
            ->select(['constellation_name as text', 'id as value'])
            ->get();



        $stationList = Station::join('systems', 'stations.system_id', '=', 'systems.id')
            ->whereIn('systems.region_id', $pullStart)
            ->where('stations.added_from_recon', 1)
            ->orderBy('name', 'asc')
            ->select(['name as text', 'stations.id as value'])->get();

        $roles = Role::whereNot('name', 'Super Admin')->orderBy('name', 'asc')->get();

        $corpIDs = Station::join('systems', 'stations.system_id', '=', 'systems.id')
            ->whereIn('systems.region_id', $pullStart)
            ->where('stations.added_from_recon', 1)
            ->pluck('corp_id');

        $corpIDs = $corpIDs->unique();

        $allianceList = Alliance::whereIn('id', function ($query) use ($corpIDs) {
            $query->select('alliance_id')
                ->from('corps')
                ->whereIn('id', $corpIDs);
        })->orderBy('ticker', 'asc')->select(['ticker as text', 'id as value'])->get();


        $itemIDs = Station::join('systems', 'stations.system_id', '=', 'systems.id')
            ->whereIn('systems.region_id', $pullStart)
            ->where('stations.added_from_recon', 1)
            ->pluck('item_id');

        $itemIDs = $itemIDs->unique();

        $items = Item::whereIn('id', $itemIDs)
            ->select(['item_name as text', 'id as value'])
            ->orderBy('text', 'asc')
            ->get();





        $roleList = $roles->map(function ($items) {
            $data['value'] = $items->id;
            $data['text'] = $items->name;
            $data['selected'] = false;

            return $data;
        });

        $userList = User::select('id', 'name')->where('id', '>', 5)->orderBy("name")->get();


        return [
            'pull' => $pull,
            'webwayStartSystems' => $webway,
            'regionlist' => $regionList,
            'systemlist' => $systemList,
            'stationlist' => $stationList,
            'constellationDropDownlist' => $constellationDropDownList,
            'regiondropdownlist' => $regionDropDownList,
            'systemdropdownlist' => $systemDropDownList,
            'allianceList' => $allianceList,
            'roles' => $roleList,
            'userList' => $userList,
            'itemList' => $items
        ];
    }
}


if (!function_exists('getStationFitBlockSolo')) {
    function getStationFitBlockSolo($id)
    {

        $station = Station::whereId($id)->first();
        $stationName = $station->name;
        $typeID = $station->item->id;

        switch ($typeID) {
            case 35832:
                $nameSlot = 'Astrahus, ' . $stationName;
                $lowSlots = ['Empty Low slot', 'Empty Low slot', 'Empty Low slot'];
                $medSlots = ['Empty Med slot', 'Empty Med slot', 'Empty Med slot', 'Empty Med slot'];
                $highSlots = ['Empty High slot', 'Empty High slot', 'Empty High slot', 'Empty High slot'];
                $rigSlots = ['Empty Rig slot', 'Empty Rig slot', 'Empty Rig slot'];
                $serviceSlots = ['Empty Service slot', 'Empty Service slot', 'Empty Service slot'];
                break;

            case 35834:
                $nameSlot = 'Keepstar, ' . $stationName;
                $lowSlots = ['Empty Low slot', 'Empty Low slot', 'Empty Low slot', 'Empty Low slot', 'Empty Low slot'];
                $medSlots = ['Empty Med slot', 'Empty Med slot', 'Empty Med slot', 'Empty Med slot', 'Empty Med slot', 'Empty Med slot'];
                $highSlots = ['Empty High slot', 'Empty High slot', 'Empty High slot', 'Empty High slot', 'Empty High slot', 'Empty High slot', 'Empty High slot', 'Empty High slot'];
                $rigSlots = ['Empty Rig slot', 'Empty Rig slot', 'Empty Rig slot'];
                $serviceSlots = ['Empty Service slot', 'Empty Service slot', 'Empty Service slot', 'Empty Service slot', 'Empty Service slot', 'Empty Service slot', 'Empty Service slot'];
                break;

            case 35827:
                $nameSlot = 'Sotiyo, ' . $stationName;
                $lowSlots = ['Empty Low slot', 'Empty Low slot', 'Empty Low slot'];
                $medSlots = ['Empty Med slot', 'Empty Med slot', 'Empty Med slot', 'Empty Med slot'];
                $highSlots = ['Empty High slot', 'Empty High slot', 'Empty High slot', 'Empty High slot', 'Empty High slot', 'Empty High slot'];
                $rigSlots = ['Empty Rig slot', 'Empty Rig slot', 'Empty Rig slot'];
                $serviceSlots = ['Empty Service slot', 'Empty Service slot', 'Empty Service slot', 'Empty Service slot', 'Empty Service slot', 'Empty Service slot'];
                break;

            case 35833:
                $nameSlot = 'Fortizar, ' . $stationName;
                $lowSlots = ['Empty Low slot', 'Empty Low slot', 'Empty Low slot', 'Empty Low slot'];
                $medSlots = ['Empty Med slot', 'Empty Med slot', 'Empty Med slot', 'Empty Med slot', 'Empty Med slot'];
                $highSlots = ['Empty High slot', 'Empty High slot', 'Empty High slot', 'Empty High slot', 'Empty High slot', 'Empty High slot'];
                $rigSlots = ['Empty Rig slot', 'Empty Rig slot', 'Empty Rig slot'];
                $serviceSlots = ['Empty Service slot', 'Empty Service slot', 'Empty Service slot', 'Empty Service slot', 'Empty Service slot'];
                break;

            case 35825:
                $nameSlot = 'Raitaru, ' . $stationName;
                $lowSlots = ['Empty Low slot'];
                $medSlots = ['Empty Med slot', 'Empty Med slot'];
                $highSlots = ['Empty High slot', 'Empty High slot', 'Empty High slot'];
                $rigSlots = ['Empty Rig slot', 'Empty Rig slot', 'Empty Rig slot'];
                $serviceSlots = ['Empty Service slot', 'Empty Service slot', 'Empty Service slot'];
                break;

            case 35826:
                $nameSlot = 'Azbel, ' . $stationName;
                $lowSlots = ['Empty Low slot', 'Empty Low slot'];
                $medSlots = ['Empty Med slot', 'Empty Med slot', 'Empty Med slot'];
                $highSlots = ['Empty High slot', 'Empty High slot', 'Empty High slot', 'Empty High slot'];
                $rigSlots = ['Empty Rig slot', 'Empty Rig slot', 'Empty Rig slot'];
                $serviceSlots = ['Empty Service slot', 'Empty Service slot', 'Empty Service slot', 'Empty Service slot', 'Empty Service slot'];
                break;

            case 35835:
                $nameSlot = 'Athanor, ' . $stationName;
                $lowSlots = ['Empty Low slot'];
                $medSlots = ['Empty Med slot', 'Empty Med slot', 'Empty Med slot'];
                $highSlots = ['Empty High slot', 'Empty High slot', 'Empty High slot'];
                $rigSlots = ['Empty Rig slot', 'Empty Rig slot', 'Empty Rig slot'];
                $serviceSlots = ['Empty Service slot', 'Empty Service slot', 'Empty Service slot'];
                break;

            case 35836:
                $nameSlot = 'Tatara, ' . $stationName;
                $lowSlots = ['Empty Low slot', 'Empty Low slot', 'Empty Low slot'];
                $medSlots = ['Empty Med slot', 'Empty Med slot', 'Empty Med slot', 'Empty Med slot'];
                $highSlots = ['Empty High slot', 'Empty High slot', 'Empty High slot', 'Empty High slot', 'Empty High slot'];
                $rigSlots = ['Empty Rig slot', 'Empty Rig slot', 'Empty Rig slot'];
                $serviceSlots = ['Empty Service slot', 'Empty Service slot', 'Empty Service slot', 'Empty Service slot', 'Empty Service slot'];
                break;

            case 47512:
                $nameSlot = "'Moreau' Fortizar, '" . $stationName;
                $lowSlots = ['Empty Low slot', 'Empty Low slot', 'Empty Low slot', 'Empty Low slot', 'Empty Low slot'];
                $medSlots = ['Empty Med slot', 'Empty Med slot', 'Empty Med slot', 'Empty Med slot', 'Empty Med slot', 'Empty Med slot'];
                $highSlots = ['Empty High slot', 'Empty High slot', 'Empty High slot', 'Empty High slot', 'Empty High slot', 'Empty High slot', 'Empty High slot'];
                $rigSlots = ['Empty Rig slot', 'Empty Rig slot', 'Empty Rig slot'];
                $serviceSlots = ['Empty Service slot', 'Empty Service slot', 'Empty Service slot', 'Empty Service slot', 'Empty Service slot', 'Empty Service slot', 'Empty Service slot', 'Empty Service slot'];
                break;

            case 47516:
                $nameSlot = '\'Prometheus\' Fortizar, ' . $stationName;
                $lowSlots = ['Empty Low slot', 'Empty Low slot', 'Empty Low slot', 'Empty Low slot'];
                $medSlots = ['Empty Med slot', 'Empty Med slot', 'Empty Med slot', 'Empty Med slot', 'Empty Med slot', 'Empty Med slot'];
                $highSlots = ['Empty High slot', 'Empty High slot', 'Empty High slot', 'Empty High slot', 'Empty High slot', 'Empty High slot', 'Empty High slot'];
                $rigSlots = ['Empty Rig slot', 'Empty Rig slot', 'Empty Rig slot'];
                $serviceSlots = ['Empty Service slot', 'Empty Service slot', 'Empty Service slot', 'Empty Service slot', 'Empty Service slot', 'Empty Service slot', 'Empty Service slot'];
                break;

            case 47514:
                $nameSlot = "'Horizon' Fortizar, '" . $stationName;
                $lowSlots = ['Empty Low slot', 'Empty Low slot', 'Empty Low slot', 'Empty Low slot'];
                $medSlots = ['Empty Med slot', 'Empty Med slot', 'Empty Med slot', 'Empty Med slot', 'Empty Med slot', 'Empty Med slot'];
                $highSlots = ['Empty High slot', 'Empty High slot', 'Empty High slot', 'Empty High slot', 'Empty High slot', 'Empty High slot', 'Empty High slot'];
                $rigSlots = ['Empty Rig slot', 'Empty Rig slot', 'Empty Rig slot'];
                $serviceSlots = ['Empty Service slot', 'Empty Service slot', 'Empty Service slot', 'Empty Service slot', 'Empty Service slot', 'Empty Service slot', 'Empty Service slot'];
                break;

            case 47515:
                $nameSlot = "'Marginis' Fortizar, '" . $stationName;
                $lowSlots = ['Empty Low slot', 'Empty Low slot', 'Empty Low slot', 'Empty Low slot', 'Empty Low slot'];
                $medSlots = ['Empty Med slot', 'Empty Med slot', 'Empty Med slot', 'Empty Med slot', 'Empty Med slot'];
                $highSlots = ['Empty High slot', 'Empty High slot', 'Empty High slot', 'Empty High slot', 'Empty High slot', 'Empty High slot', 'Empty High slot'];
                $rigSlots = ['Empty Rig slot', 'Empty Rig slot', 'Empty Rig slot'];
                $serviceSlots = ['Empty Service slot', 'Empty Service slot', 'Empty Service slot', 'Empty Service slot', 'Empty Service slot', 'Empty Service slot', 'Empty Service slot', 'Empty Service slot'];
                break;

            case 35832:
                $nameSlot = "'Marginis' Fortizar, ' . $stationName";
                $lowSlots = ['Empty Low slot', 'Empty Low slot', 'Empty Low slot', 'Empty Low slot', 'Empty Low slot'];
                $medSlots = ['Empty Med slot', 'Empty Med slot', 'Empty Med slot', 'Empty Med slot', 'Empty Med slot'];
                $highSlots = ['Empty High slot', 'Empty High slot', 'Empty High slot', 'Empty High slot', 'Empty High slot', 'Empty High slot', 'Empty High slot'];
                $rigSlots = ['Empty Rig slot', 'Empty Rig slot', 'Empty Rig slot'];
                $serviceSlots = ['Empty Service slot', 'Empty Service slot', 'Empty Service slot', 'Empty Service slot', 'Empty Service slot', 'Empty Service slot', 'Empty Service slot', 'Empty Service slot'];
                break;

            case 47513:
                $nameSlot = "'Draccous' Fortizar, '" . $stationName;
                $lowSlots = ['Empty Low slot', 'Empty Low slot', 'Empty Low slot', 'Empty Low slot', 'Empty Low slot'];
                $medSlots = ['Empty Med slot', 'Empty Med slot', 'Empty Med slot', 'Empty Med slot', 'Empty Med slot'];
                $highSlots = ['Empty High slot', 'Empty High slot', 'Empty High slot', 'Empty High slot', 'Empty High slot', 'Empty High slot', 'Empty High slot'];
                $rigSlots = ['Empty Rig slot', 'Empty Rig slot', 'Empty Rig slot'];
                $serviceSlots = ['Empty Service slot', 'Empty Service slot', 'Empty Service slot', 'Empty Service slot', 'Empty Service slot', 'Empty Service slot', 'Empty Service slot'];
                break;
        }


        $items = StationItemJoin::whereStationId($id)->with('item')->get();
        foreach ($items as $item) {
            $slot = $item->item->slot_value;
            $name = $item->item->item_name;
            switch ($slot) {
                case 1:
                    foreach ($serviceSlots as $key => $text) {
                        if ($text == 'Empty Service slot') {
                            $serviceSlots[$key] = $name;
                            break;
                        }
                    }
                    break;
                case 2:
                    foreach ($rigSlots as $key => $text) {
                        if ($text == 'Empty Rig slot') {
                            $rigSlots[$key] = $name;
                            break;
                        }
                    }
                    break;
                case 3:
                    foreach ($lowSlots as $key => $text) {
                        if ($text == 'Empty Low slot') {
                            $lowSlots[$key] = $name;
                            break;
                        }
                    }
                    break;
                case 4:
                    foreach ($medSlots as $key => $text) {
                        if ($text == 'Empty Med slot') {
                            $medSlots[$key] = $name;
                            break;
                        }
                    }
                    break;
                case 5:
                    foreach ($highSlots as $key => $text) {
                        if ($text == 'Empty High slot') {
                            $highSlots[$key] = $name;
                            break;
                        }
                    }
                    break;
            }
        }

        // dd($nameSlot, $lowSlots, $medSlots, $highSlots, $rigSlots, $serviceSlots);

        // Build the text block using the slot arrays
        $textBlock = buildTextBlock($lowSlots, $medSlots, $highSlots, $rigSlots, $serviceSlots, $nameSlot);
        // dd($textBlock);
        return $textBlock;
    }
}



if (!function_exists('buildTextBlock')) {
    function buildTextBlock($lowSlots, $medSlots, $highSlots, $rigSlots, $serviceSlots, $nameSlot)
    {
        $textBlock = "[$nameSlot]\n\n";

        foreach ($lowSlots as $lowSlot) {
            $textBlock .= ($lowSlot == 'Empty Low slot') ? "[Empty Low slot]\n" : "$lowSlot\n";
        }
        $textBlock .= "\n";

        foreach ($medSlots as $medSlot) {
            $textBlock .= ($medSlot == 'Empty Med slot') ? "[Empty Med slot]\n" : "$medSlot\n";
        }
        $textBlock .= "\n";

        foreach ($highSlots as $highSlot) {
            $textBlock .= ($highSlot == 'Empty High slot') ? "[Empty High slot]\n" : "$highSlot\n";
        }
        $textBlock .= "\n";

        foreach ($rigSlots as $rigSlot) {
            $textBlock .= ($rigSlot == 'Empty Rig slot') ? "[Empty Rig slot]\n" : "$rigSlot\n";
        }
        $textBlock .= "\n";

        foreach ($serviceSlots as $serviceSlot) {
            $textBlock .= ($serviceSlot == 'Empty Service slot') ? "[Empty Service slot]\n" : "$serviceSlot\n";
        }

        return $textBlock;
    }
}
