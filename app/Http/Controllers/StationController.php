<?php

namespace App\Http\Controllers;

use App\Events\RcMoveDelete;
use App\Events\RcMoveUpdate;
use App\Events\StationCoreUpdate;
use App\Events\StationDeadStationSheet;
use App\Events\WatchListStationPageUpdate;
use App\Models\Alliance;
use App\Models\Corp;
use App\Models\Item;
use App\Models\Station;
use App\Models\StationItemJoin;
use App\Models\StationItems;
use App\Models\System;
use GuzzleHttp\Client as GuzzleHttpClient;
use GuzzleHttp\Utils;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;

class StationController extends Controller
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

    public function updateStationSheetWebway($id)
    {
        $station_ids = Station::where('system_id', $id)->pluck('id');

        foreach ($station_ids as $id) {
            $message = StationRecordsSolo(6, $id);
            $watchListIDs = getStationWatchListIDs($message->id);
            foreach ($watchListIDs as $watchListID) {
                $flag = collect([
                    'message' => $message,
                    'id' => $watchListID,
                ]);
                broadcast(new WatchListStationPageUpdate($flag));
            }
        }
    }

    public function stationSheet()
    {
        return ['stations' =>  getAllallowedStations()];
    }


    public function reconRegionPull()
    {
        Artisan::call('update:reconstations');
    }

    public function taskRequest(Request $request)
    {
        $variables = json_decode(base64_decode(getenv('PLATFORM_VARIABLES')), true);
        $message = [
            'station_id' => $request->station_id,
            'task_flag' => 1,
        ];

        $flag = collect([
            'message' => $message,
        ]);
        broadcast(new StationCoreUpdate($flag));

        $s = System::where('id', $request->system_id)->get();
        foreach ($s as $s) {
            $s->update(['task_flag' => 1]);
        }
        $url = 'https://recon.gnf.lt/api/task/add';
        $client = new GuzzleHttpClient();
        $headers = [
            // 'x-gsf-user' => env('RECON_USER', 'DANCE2'),
            'x-gsf-user' => env('RECON_USER', ($variables && array_key_exists('RECON_USER', $variables)) ? $variables['RECON_USER'] : 'DANCE2'),
            // 'token' =>  env('RECON_TOKEN', "DANCE")
            'token' => env('RECON_TASK_TOKEN', ($variables && array_key_exists('RECON_TASK_TOKEN', $variables)) ? $variables['RECON_TASK_TOKEN'] : 'DANCE2'),

        ];

        $body = [
            'system' => $request->system_name,
            'task' => $request->structure_name,
            'username' => $request->username,
        ];
        $response = $client->request('POST', $url, [
            'headers' => $headers,
            'json' => $body,
            'http_errors' => false,
        ]);
    }

    public static function reconPullbyname(Request $request)
    {
        $variables = json_decode(base64_decode(getenv('PLATFORM_VARIABLES')), true);

        $name = preg_replace("/\([^\)]+\)(\R|$)/", '$1', $request->stationName);
        $name = rtrim($name);
        // dd($name);
        $url = 'https://recon.gnf.lt/api/structure/' . $name;

        $client = new GuzzleHttpClient();
        $headers = [
            'x-gsf-user' => env('RECON_USER', ($variables && array_key_exists('RECON_USER', $variables)) ? $variables['RECON_USER'] : 'DANCE2'),
            'token' => env('RECON_TOKEN', ($variables && array_key_exists('RECON_TOKEN', $variables)) ? $variables['RECON_TOKEN'] : 'DANCE2'),
        ];
        $response = $client->request('GET', $url, [
            'headers' => $headers,
            'http_errors' => false,
        ]);

        // dd($response);

        $stationdata = Utils::jsonDecode($response->getBody(), true);
        if ($response->getStatusCode() == 200) {
            if ($stationdata == 'Error, Structure Not Found') {
                $stationCheck = station::where('name', $name)->get();
                $check = $stationCheck->count();
                if ($check > null) {
                    $data = [];
                } else {
                    $data = [
                        'state' => 2,
                        'station_name' => $name,
                    ];

                    return $data;
                }
            } else {
                $checkifthere = Station::find($stationdata['str_structure_id']);
                $showMain = 0;
                $showChill = 0;
                $showRcMove = 0;
                $showWelp = 0;
                if ($checkifthere) {
                    $showMain = $checkifthere->show_on_main;
                    $showChill = $checkifthere->show_on_chill;
                    $showRcMove = $checkifthere->show_on_rc_move;
                    $showWelp = $checkifthere->show_on_welp;
                    if ($request->show == 1) {
                        $showMain = 1;
                    }
                    if ($request->show == 2) {
                        $showChill = 1;
                    }
                    if ($request->show == 3) {
                        $showRcMove = 1;
                    }

                    if ($request->show == 4) {
                        $showWelp = 1;
                    }
                }

                $core = 0;
                if ($stationdata['str_cored'] == 'Yes') {
                    $core = 1;
                }
                $standing = 0;
                $corp = Corp::where('id', $stationdata['str_owner_corporation_id'])->first();
                $alliance = Alliance::where('id', $corp->alliance_id)->first();
                if ($alliance) {
                    if ($corp->standing > $alliance->standing) {
                        $standing = $corp->standing;
                    } else {
                        $standing = $alliance->standing;
                    }
                } else {
                    $standing = $corp->standing;
                }

                Station::updateOrCreate(['id' => $stationdata['str_structure_id']], [
                    'name' => $stationdata['str_name'],
                    'standing' => $standing,
                    'system_id' => $stationdata['str_system_id'],
                    'corp_id' => $stationdata['str_owner_corporation_id'],
                    'alliance_id' => $stationdata['str_owner_alliance_id'] ?? 0,
                    'item_id' => $stationdata['str_type_id'],
                    'text' => null,
                    'station_status_id' => 10,
                    'user_id' => null,
                    'timestamp' => now(),
                    'r_hash' => $stationdata['str_structure_id_md5'],
                    'r_updated_at' => $stationdata['updated_at'],
                    'r_fitted' => $stationdata['str_has_no_fitting'],
                    'r_capital_shipyard' => $stationdata['str_capital_shipyard'],
                    'r_hyasyoda' => $stationdata['str_hyasyoda'],
                    'r_invention' => $stationdata['str_invention'],
                    'r_manufacturing' => $stationdata['str_manufacturing'],
                    'r_research' => $stationdata['str_research'],
                    'r_supercapital_shipyard' => $stationdata['str_supercapital_shipyard'],
                    'r_biochemical' => $stationdata['str_biochemical'],
                    'r_hybrid' => $stationdata['str_hybrid'],
                    'r_moon_drilling' => $stationdata['str_moon_drilling'],
                    'r_reprocessing' => $stationdata['str_reprocessing'],
                    'r_point_defense' => $stationdata['str_point_defense'],
                    'r_dooms_day' => $stationdata['str_dooms_day'],
                    'r_guide_bombs' => $stationdata['str_guide_bombs'],
                    'r_anti_cap' => $stationdata['str_anti_cap'],
                    'r_anti_subcap' => $stationdata['str_anti_subcap'],
                    'r_t2_rigged' => $stationdata['str_t2_rigged'],
                    'r_cloning' => $stationdata['str_cloning'],
                    'r_composite' => $stationdata['str_composite'],
                    'r_cored' => $core,
                    'show_on_main' => $showMain,
                    'show_on_chill' => $showChill,
                    'show_on_rc_move' => $showRcMove,
                    'show_on_welp' => $showWelp,
                    'added_by_user_id' => Auth::id(),
                    'added_from_recon' => 1,
                ]);
                if ($stationdata['str_has_no_fitting'] != null) {
                    $items = Utils::jsonDecode($stationdata['str_fitting'], true);
                    foreach ($items as $item) {
                        StationItems::where('id', $item['type_id'])->get()->count();
                        if (StationItems::where('id', $item['type_id'])->get()->count() == 0) {
                            StationItems::Create(['id' => $item['type_id'], 'item_name' => $item['name']]);
                        }
                        StationItemJoin::create(['station_item_id' => $item['type_id'], 'station_id' => $stationdata['str_structure_id']]);
                    }
                }

                $corp = Corp::where('id', $stationdata['str_owner_corporation_id'])->first();
                $item = Item::where('id', $stationdata['str_type_id'])->first();
                $system = System::where('id', $stationdata['str_system_id'])->first();
                $data = [];
                $data = [
                    'state' => 3,
                    'station_id' => $stationdata['str_structure_id'],
                    'station_name' => $stationdata['str_name'],
                    'structure_name' => $item->item_name,
                    'structure_id' => $item->id,
                    'system_name' => $system->system_name,
                    'system_id' => $system->id,
                    'corp_ticker' => $corp->ticker,
                    'corp_id' => $corp->id,
                ];

                return $data;
            }
        } else {
            $stationCheck = station::where('name', $name)->first();
            if ($stationCheck) {
                $station = Station::where('id', $stationCheck->id)->first();

                return $data = [
                    'state' => 3,
                    'station_id' => $station->id,
                    'station_name' => $station->name,
                    'structure_name' => $station->item->item_name,
                    'structure_id' => $station->id,
                    'system_name' => $station->system->system_name,
                    'system_id' => $station->system_id,
                    'corp_ticker' => $station->corp->ticker,
                    'corp_id' => $station->corp_id,

                ];
            } else {
                $data = [
                    'state' => 2,
                    'station_name' => $name,
                ];

                return $data;
            }
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request#
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        $id = Station::where('added_from_recon', 0)->max('id');
        if ($id == null) {
            $id = 1;
        } else {
            $id = $id + 1;
        }

        $new = new Station();
        $new->id = $id;
        $new->name = $request->name;
        $new->system_id = $request->system_id;
        $new->corp_id = $request->corp_id;
        $new->item_id = $request->item_id;
        $new->station_status_id = $request->station_status_id;
        $new->out_time = $request->out_time;
        $new->status_update = now();
        $new->timestamp = now();
        $new->timer_image_link = $request->timer_image_link;
        $new->added_by_user_id = $user->id;
        $new->r_cored = 0;
        $new->log_helper = 2;

        if ($user->can('add_timer')) {
            $new->show_on_rc_move = 0;
        } else {
            $new->show_on_rc_move = 1;
        }



        $corp = Corp::where('id', $new->corp_id)->first();
        $corpStanding = $corp->standing;
        $allianceStanding = null;
        if (Alliance::where('id', $new->alliance_id)->first()) {
            $alliance = Alliance::where('id', $new->alliance_id)->first();
            $allianceStanding = $alliance->standing;
        }

        if ($allianceStanding) {
            if ($corpStanding > $allianceStanding) {
                $standing = $corpStanding;
            } else {
                $standing = $allianceStanding;
            }
        } else {
            $standing = $corpStanding;
        }

        $new->standing = $standing;
        $new->save();
    }


    public function addStationTimer(Request $request, $id)
    {
        $user = Auth::user();
        $station = Station::whereId($id)->first();
        $station->station_status_id = $request->station_status_id;
        $station->out_time = $request->out_time;
        $station->status_update = now();
        $station->timestamp = now();
        $station->timer_image_link = $request->timer_image_link;
        $station->added_by_user_id = $user->id;
        $station->r_cored = 0;
        $station->log_helper = 2;
        $station->show_on_coord = 0;
        if ($user->can('add_timer')) {
            $station->show_on_rc_move = 0;
        } else {
            $station->show_on_rc_move = 1;
        }

        $station->save();

        $message = StationRecordsSolo(6, $id);
        $watchListIDs = getStationWatchListIDs($message->id);
        foreach ($watchListIDs as $watchListID) {
            $flag = collect([
                'flag' => 1,
                'message' => $message,
                'id' => $watchListID,
            ]);
            broadcast(new WatchListStationPageUpdate($flag));
        }

        $flag = collect([
            'id' => $id,
        ]);
        broadcast(new StationDeadStationSheet($flag));
    }


    public function editStation(Request $request, $id)
    {
        $user = Auth::user();
        if ($user->can('finish_move_timers')) {
            $station = Station::whereId($id)->first();
            $station->name = $request->name;
            $station->system_id = $request->system_id;
            $station->corp_id = $request->corp_id;
            $station->item_id = $request->item_id;
            $station->station_status_id = $request->station_status_id;
            $station->out_time = $request->out_time;
            $station->timer_image_link = $request->timer_image_link;
            $station->status_update = now();
            $station->timestamp = now();
            $station->log_helper = 3;
            $station->show_on_coord = 0;

            $station->save();

            $message = StationRecordsSolo(6, $id);
            $watchListIDs = getStationWatchListIDs($message->id);
            foreach ($watchListIDs as $watchListID) {
                $flag = collect([
                    'flag' => 1,
                    'message' => $message,
                    'id' => $watchListID,
                ]);
                broadcast(new WatchListStationPageUpdate($flag));
            }

            $message = stationRecordSolo($id);
            $flag = collect([
                'message' => $message,
            ]);
            broadcast(new RcMoveUpdate($flag));

            $flag = collect([
                'id' => $id,
            ]);
            broadcast(new StationDeadStationSheet($flag));
        } else {
            return null;
        }
    }

    public function updateTimerStatus(Request $request, $id)
    {
        $user = Auth::user();
        if ($user->can('finish_move_timers')) {
            $station = Station::whereId($id)->first();
            $station->show_on_rc_move = 0;
            if ($request->status == 2) {
                $station->station_status_id = 16;
                $station->out_time = null;
                $station->status_update = now();
                $station->log_helper = 4;
                $station->timer_image_link = null;
            } else {
                $station->log_helper = 5;
            }
            $station->save();

            $flag = collect([
                'id' => $id,
            ]);
            broadcast(new RcMoveDelete($flag));
        } else {
            return null;
        }
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



    public function updateStationSheet(Request $request, $id)
    {
        $s = Station::whereId($id)->first();
        $s->station_status_id = $request->station_status_id;
        $s->show_on_rc = 0;
        $s->show_on_coord = 1;
        $s->rc_id = null;
        $s->rc_fc_id = null;
        $s->rc_gsol_id = null;
        $s->rc_recon_id = null;
        $s->log_helper = 1;
        $s->save();




        $message = StationRecordsSolo(6, $id);
        $watchListIDs = getStationWatchListIDs($message->id);
        foreach ($watchListIDs as $watchListID) {
            $flag = collect([
                'flag' => 1,
                'message' => $message,
                'id' => $watchListID,
            ]);
            broadcast(new WatchListStationPageUpdate($flag));
        }
    }
}
