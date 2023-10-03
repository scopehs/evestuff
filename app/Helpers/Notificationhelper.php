<?php

use App\Events\StationDataSet;
use App\Events\StationInfoSet;
use App\Events\StationNotificationDelete;
use App\Events\StationNotificationNew;
use App\Events\StationNotificationUpdate;
use App\Events\TowerChanged;
use App\Events\TowerDelete;
use App\Events\WatchListStationPageUpdate;
use App\Models\Alliance;
use App\Models\Corp;
use App\Models\Notification;
use App\Models\Station;
use App\Models\StationItemJoin;
use App\Models\StationItems;
use App\Models\StationNotes;
use App\Models\StationNotification;
use App\Models\StationNotificationArmor;
use App\Models\StationNotificationShield;
use App\Models\System;
use App\Models\Temp_notifcation;
use App\Models\Tower;
use App\Models\TowerRecord;
use GuzzleHttp\Client as GuzzleHttpClient;
use GuzzleHttp\Utils;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Spatie\Activitylog\Models\Activity;
use Symfony\Component\Yaml\Yaml;

if (!function_exists('reconRegionPull')) {
    function reconRegionPull($id)
    {
        $variables = json_decode(base64_decode(getenv('PLATFORM_VARIABLES')), true);
        $url = 'https://recon.gnf.lt/api/structures/hostile/region/' . $id;

        $client = new GuzzleHttpClient();
        $headers = [
            // 'x-gsf-user' => e     nv('RECON_USER', 'DANCE2'),
            'x-gsf-user' => env('RECON_USER', ($variables && array_key_exists('RECON_USER', $variables)) ? $variables['RECON_USER'] : 'DANCE2'),
            // 'token' =>  env('RECON_TOKEN', "DANCE")
            'token' => env('RECON_TOKEN', ($variables && array_key_exists('RECON_TOKEN', $variables)) ? $variables['RECON_TOKEN'] : 'DANCE2'),

        ];
        $response = $client->request('GET', $url, [
            'headers' => $headers,
            'http_errors' => false,
        ]);
        $data = Utils::jsonDecode($response->getBody(), true);

        return $data;
    }
}

if (!function_exists('reconRegionPullIdCheck')) {
    function reconRegionPullIdCheck($id)
    {

        $variables = json_decode(base64_decode(getenv('PLATFORM_VARIABLES')), true);
        $i = 0;
        $stationUpdated = false;

        $url = 'https://recon.gnf.lt/api/structure/' . $id;
        $client = new GuzzleHttpClient();
        $headers = [
            // 'x-gsf-user' => env('RECON_USER', 'DANCE2'),
            'x-gsf-user' => env('RECON_USER', ($variables && array_key_exists('RECON_USER', $variables)) ? $variables['RECON_USER'] : 'DANCE2'),
            // 'token' =>  env('RECON_TOKEN', "DANCE")
            'token' => env('RECON_TOKEN', ($variables && array_key_exists('RECON_TOKEN', $variables)) ? $variables['RECON_TOKEN'] : 'DANCE2'),

        ];
        $response = $client->request('GET', $url, [
            'headers' => $headers,
            'http_errors' => false,
        ]);

        $stationdata = Utils::jsonDecode($response->getBody(), true);
        if ($stationdata == 'Error, Structure Not Found') {
            $s = Station::where('id', $id)->first();
            $s->delete();
            $flag = collect([
                'id' => $id,
            ]);
            broadcast(new StationNotificationDelete($flag));
            $s = StationItemJoin::where('station_id', $id)->get();
            foreach ($s as $s) {
                $s->delete();
            }
        } else {
            $core = 0;
            $standing = 0;
            $corp = Corp::where('id', $stationdata['str_owner_corporation_id'])->first();
            if (!$corp) {
                $corpPull = 0;
                do {
                    $response = Http::withHeaders([
                        'Content-Type' => 'application/json',
                        'Accept' => 'application/json',
                        'User-Agent' => 'evestuff.online evestuff@lol.com',
                    ])->get('https://esi.evetech.net/latest/corporations/' . $stationdata['str_owner_corporation_id'] . '/?datasource=tranquility');
                    if ($response->successful()) {
                        $corpPull = 3;
                        $corpInfo = $response->collect();
                        Corp::updateOrCreate(
                            ['id' => $stationdata['str_owner_corporation_id']],
                            [
                                'alliance_id' => null,
                                'name' => $corpInfo->get('name'),
                                'ticker' => $corpInfo->get('ticker'),
                                'color' => 0,
                                'standing' => 0,
                                'active' => 1,
                                'url' => 'https://images.evetech.net/Corporation/' . $stationdata['str_owner_corporation_id'] . '_64.png',

                            ]
                        );
                        $corp = Corp::where('id', $stationdata['str_owner_corporation_id'])->first();
                    } else {
                        $headers = $response->headers();
                        $sleep = $headers['X-Esi-Error-Limit-Reset'][0];
                        sleep($sleep);
                        $corpPull++;
                    }
                } while ($corpPull != 3);
            }
            $alliance = Alliance::where('id', $corp->alliance_id)->first();
            if ($alliance && $alliance->standing != 0) {
                $standing = ($corp->standing > $alliance->standing) ? $corp->standing : $alliance->standing;
            } else {
                $standing = $corp->standing;
            }

            if ($stationdata['str_cored'] == 'Yes') {
                $core = 1;
            }

            $s = StationItemJoin::where('station_id', $id)->get();
            foreach ($s as $s) {
                $s->delete();
            }

            if (Station::where('id', $id)->exists()) {
                $stationUpdate = Station::where('id', $id)->first();
                $stationUpdate->import_flag = 0;
                $stationUpdate->save();
            } else {
                $stationUpdate = new Station();
            }
            $stationUpdate->id = $stationdata['str_structure_id'];
            $stationUpdate->name = $stationdata['str_name'];
            $stationUpdate->standing = $standing;
            $stationUpdate->r_hash = $stationdata['str_structure_id_md5'];
            $stationUpdate->corp_id = $stationdata['str_owner_corporation_id'];
            $stationUpdate->r_updated_at = $stationdata['updated_at'];
            $stationUpdate->r_fitted = $stationdata['str_has_no_fitting'];
            $stationUpdate->r_capital_shipyard = $stationdata['str_capital_shipyard'];
            $stationUpdate->r_hyasyoda = $stationdata['str_hyasyoda'];
            $stationUpdate->r_invention = $stationdata['str_invention'];
            $stationUpdate->r_manufacturing = $stationdata['str_manufacturing'];
            $stationUpdate->r_research = $stationdata['str_research'];
            $stationUpdate->r_supercapital_shipyard = $stationdata['str_supercapital_shipyard'];
            $stationUpdate->r_biochemical = $stationdata['str_biochemical'];
            $stationUpdate->r_hybrid = $stationdata['str_hybrid'];
            $stationUpdate->r_moon_drilling = $stationdata['str_moon_drilling'];
            $stationUpdate->r_reprocessing = $stationdata['str_reprocessing'];
            $stationUpdate->r_point_defense = $stationdata['str_point_defense'];
            $stationUpdate->r_dooms_day = $stationdata['str_dooms_day'];
            $stationUpdate->r_guide_bombs = $stationdata['str_guide_bombs'];
            $stationUpdate->r_anti_cap = $stationdata['str_anti_cap'];
            $stationUpdate->r_anti_subcap = $stationdata['str_anti_subcap'];
            $stationUpdate->r_t2_rigged = $stationdata['str_t2_rigged'];
            $stationUpdate->r_cloning = $stationdata['str_cloning'];
            $stationUpdate->r_composite = $stationdata['str_composite'];
            $stationUpdate->r_cored = $core;
            $stationUpdate->system_id = $stationdata['str_system_id'];
            $stationUpdate->item_id = $stationdata['str_type_id'];
            $stationUpdate->added_from_recon = 1;
            $stationUpdate->import_flag = 1;
            if (count($stationUpdate->getDirty()) > 1) {
                $stationUpdated = true;
            }
            $stationUpdate->save();

            $status_id = Station::where('id', $id)->value('station_status_id');
            if ($status_id == 7) {
                $s = Station::where('id', $id)->first();
                $s->station_status_id = 16;
                $s->save();
            }

            $station = Station::where('id', $id)->first();
            if ($station->show_on_rc != 1 && $station->show_on_rc_move != 1) {
                $station->show_on_coord = 1;
                $station->save();

                if ($station->station_status_id == 10) {
                    $station = Station::where('id', $id)->first();
                    $station->station_status_id = 1;
                    $station->save();
                }
            }

            if ($stationdata['str_has_no_fitting'] != null) {
                if ($stationdata['str_has_no_fitting'] != 'No Fitting') {
                    $s = StationItemJoin::where('station_id', $id)->get();
                    foreach ($s as $s) {
                        $s->delete();
                    }
                    if ($stationdata['str_fitting']) {
                        $items = Utils::jsonDecode($stationdata['str_fitting'], true);
                        foreach ($items as $item) {
                            StationItems::where('id', $item['type_id'])->get()->count();
                            if (StationItems::where('id', $item['type_id'])->whereNull('slot')->get()->count() == 0) {
                                StationItems::updateOrCreate(
                                    ['id' => $item['type_id']],
                                    ['item_name' => $item['name']]
                                );
                                $url = 'https://esi.evetech.net/latest/universe/types/' . $item['type_id'] . '/';
                                $response = Http::withHeaders([
                                    'Content-Type' => 'application/json',
                                    'Accept' => 'application/json',
                                    'User-Agent' => 'evestuff.online evestuff@lol.com',
                                ])->get($url);
                                $itemdata = $response->json();
                                $dogs = $itemdata['dogma_effects'];
                                foreach ($dogs as $dog) {
                                    $newStationItem = StationItems::where('id', $item['type_id'])->first();
                                    if ($dog['effect_id'] == 11) {
                                        $newStationItem->slot = "Low";
                                        $newStationItem->slot_value = 3;
                                        $newStationItem->save();
                                    }
                                    if ($dog['effect_id'] == 12) {
                                        $newStationItem->slot = "High";
                                        $newStationItem->slot_value = 5;
                                        $newStationItem->save();
                                    }
                                    if ($dog['effect_id'] == 13) {
                                        $newStationItem->slot = "Medium";
                                        $newStationItem->slot_value = 4;
                                        $newStationItem->save();
                                    }
                                    if ($dog['effect_id'] == 6306) {
                                        $newStationItem->slot = "Service";
                                        $newStationItem->slot_value = 1;
                                        $newStationItem->save();
                                    }
                                    if ($dog['effect_id'] == 2663) {
                                        $newStationItem->slot = "Rig";
                                        $newStationItem->slot_value = 2;
                                        $newStationItem->save();
                                    }
                                }
                            }
                            StationItemJoin::create(['station_item_id' => $item['type_id'], 'station_id' => $id]);
                        }
                        $fitText = getStationFitBlockSolo($id);
                        Station::where('id', $id)->update(['fit_text' => $fitText]);
                    }
                }
            }

            if ($stationUpdate->wasRecentlyCreated || $stationUpdated) {
                $message = StationRecordsSolo(5, $id);
                if ($message) {
                    $watchListIDs = getStationWatchListIDs($stationUpdate->id);
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
        }
    }
}

if (!function_exists('reconPull')) {
    function reconPull($id)
    {
        $variables = json_decode(base64_decode(getenv('PLATFORM_VARIABLES')), true);
        $url = 'https://recon.gnf.lt/api/structure/' . $id;

        $client = new GuzzleHttpClient();
        $headers = [
            // 'x-gsf-user' => env('RECON_USER', 'DANCE2'),
            'x-gsf-user' => env('RECON_USER', ($variables && array_key_exists('RECON_USER', $variables)) ? $variables['RECON_USER'] : 'DANCE2'),
            // 'token' =>  env('RECON_TOKEN', "DANCE")
            'token' => env('RECON_TOKEN', ($variables && array_key_exists('RECON_TOKEN', $variables)) ? $variables['RECON_TOKEN'] : 'DANCE2'),

        ];
        $response = $client->request('GET', $url, [
            'headers' => $headers,
            'http_errors' => false,
        ]);
        $data = Utils::jsonDecode($response->getBody(), true);
        if ($data == 'Error, Structure Not Found') {
            authcheck();
            $stationdata = authpull('station', $id);

            return $stationdata;
        } else {
            return $data;
        }
    }
}
if (!function_exists('dubp')) {
    function dubp()
    {
        $dups = Station::groupBy('name')->select('name', DB::raw('count(*) as total'))->get();

        foreach ($dups as $dup) {

            if ($dup->total > 1) {
                echo $dup->name . ' ' . $dup->total;
                $stations = Station::where('name', $dup->name)->orderByDesc('id')->get();



                $stations[0]->update([

                    'user_id' => $stations[1]['user_id'] ?? null,
                    'text' => $stations[1]['text'] ?? null,
                    'attack_notes' => $stations[1]['attack_notes'] ?? null,
                    'attack_adash_link' => $stations[1]['attack_adash_link'] ?? null,
                    'station_status_id' => $stations[1]['station_status_id'] ?? null,
                    'gunner_id' => $stations[1]['gunner_id'] ?? null,
                    'out_time' => $stations[1]['out_time'] ?? null,
                    'repair_time' => $stations[1]['repair_time'] ?? null,
                    'ammo_request_id' => $stations[1]['ammo_request_id'] ?? null,
                    'status_update' => $stations[1]['status_update'] ?? null,
                    'timestamp' => $stations[1]['timestamp'] ?? null,
                    'show_on_main' => $stations[1]['show_on_main'] ?? null,
                    'show_on_chill' => $stations[1]['show_on_chill'] ?? null,
                    'show_on_welp' => $stations[1]['show_on_welp'] ?? null,
                    'show_on_rc' => $stations[1]['show_on_rc'] ?? null,
                    'show_on_rc_move' => $stations[1]['show_on_rc_move'] ?? null,
                    'show_on_coord' => $stations[1]['show_on_coord'] ?? null,
                    'added_by_user_id' => $stations[1]['added_by_user_id'] ?? null,
                    'timer_image_link' => $stations[1]['timer_image_link'] ?? null,
                    'rc_id' => $stations[1]['rc_id'] ?? null,
                    'rc_fc_id' => $stations[1]['rc_fc_id'] ?? null,
                    'rc_gsol_id' => $stations[1]['rc_gsol_id'] ?? null,
                    'rc_recon_id' => $stations[1]['rc_recon_id'] ?? null,

                ]);

                $oldStationId = $stations[1]->id;
                StationNotes::where('station_id', $oldStationId)->update(['station_id' => $stations[0]->id]);
                Activity::where('subject_id', $oldStationId)->where('subject_type', 'App\Station')->update(['subject_id' => $stations[0]->id]);
                Station::whereId($oldStationId)->delete();
                $flag = collect([
                    'id' => $stations[1]->id,
                ]);
                broadcast(new StationNotificationDelete($flag));
            }
        }
    }
}
if (!function_exists('reconNameUpdate')) {
    function reconNameUpdate($id)
    {
        $variables = json_decode(base64_decode(getenv('PLATFORM_VARIABLES')), true);
        $station = Station::whereId($id)->first();
        $url = 'https://recon.gnf.lt/api/structure/' . $station->name;
        $client = new GuzzleHttpClient();
        $headers = [
            // 'x-gsf-user' => env('RECON_USER', 'DANCE2'),
            'x-gsf-user' => env('RECON_USER', ($variables && array_key_exists('RECON_USER', $variables)) ? $variables['RECON_USER'] : 'DANCE2'),
            // 'token' =>  env('RECON_TOKEN', "DANCE")
            'token' => env('RECON_TOKEN', ($variables && array_key_exists('RECON_TOKEN', $variables)) ? $variables['RECON_TOKEN'] : 'DANCE2'),

        ];
        $response = $client->request('GET', $url, [
            'headers' => $headers,
            'http_errors' => false,
        ]);

        $stationdata = Utils::jsonDecode($response->getBody(), true);
        if ($stationdata == 'Error, Structure Not Found') {
            $station->import_flag = 1;
            $station->save();
        } else {
            $oldupdate = $station->r_updated_at;
            if ($oldupdate != $stationdata['updated_at']) {
                $s = System::where('id', $station->system_id)->get();
                foreach ($s as $s) {
                    $s->update(['task_flag' => 0]);
                }
            }
            $core = 0;
            if ($stationdata['str_cored'] == 'Yes') {
                $core = 1;
            }

            $s = Station::where('name', $station->name)->first();

            $s->update([
                'id' => $stationdata['str_structure_id'],
                'r_hash' => $stationdata['str_structure_id_md5'],
                'corp_id' => $stationdata['str_owner_corporation_id'],
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
                'added_from_recon' => 1,
                'import_flag' => 1,
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
        }
        $flag = [
            'message' => 'yoyo',
        ];
        broadcast(new StationDataSet($flag));
        broadcast(new StationInfoSet($flag));
    }
}


if (!function_exists('test')) {
    function test($var, $show)
    {
        $time = $var['timestamp'];
        $time = fixtime($time);
        $data = [];
        $text = $var['text'];
        $text = str_replace('solarSystemID', 'system_id', $text);
        $text = str_replace('structureTypeID', 'item_id', $text);
        $text = Yaml::parse($text);
        $current = now();

        if ($var['type'] == 'StructureUnderAttack' || $var['type'] == 'StructureLostShields' || $var['type'] == 'StructureLostArmor') {
            $stationnotenumber = StationNotification::where('station_id', $text['structureID'])->max('id');
            $stationshieldnumber = StationNotificationShield::where('station_id', $text['structureID'])->max('id');
            $stationarmornumber = StationNotificationArmor::where('station_id', $text['structureID'])->max('id');
            $maxNotificationID = max($stationnotenumber, $stationshieldnumber, $stationarmornumber);
            if ($maxNotificationID == null || $maxNotificationID == 0) {
                $maxNotificationID == 1;
            }
            $station_id = [
                'station_id' => $text['structureID'],
            ];
        } elseif ($var['type'] == 'AllAnchoringMsg') {
            $towernumber = Tower::max('id');
            if ($towernumber == null || $towernumber == 0) {
                $towernumber = 1;
            }
            $moon_id = [
                'moon_id' => $text['moonID'],
            ];
        } elseif ($var['type'] == 'StructureDestroyed') {
            $s = Station::where('id', $text['structureID'])->get();
            foreach ($s as $s) {
                $s->update(['station_status_id' => 7, 'out_time' => null]);
            }
            $s = StationNotificationShield::where('station_id', $text['structureID'])->get();
            foreach ($s as $s) {
                $s->delete();
            }
            $s = StationNotificationArmor::where('station_id', $text['structureID'])->get();
            foreach ($s as $s) {
                $s->delete();
            }
            $s = StationItemJoin::where('station_id', $text['structureID'])->get();
            foreach ($s as $s) {
                $s->delete();
            }
        }

        // dd($data);

        if ($var['type'] == 'AllAnchoringMsg') {
            // if ($var['notification_id'] > $towernumber) {

            $time = $var['timestamp'];
            $time = fixtime($time);
            $data = [];
            $text = $var['text'];
            $text = str_replace('solarSystemID', 'system_id', $text);
            $text = str_replace('structureTypeID', 'item_id', $text);
            $text = Yaml::parse($text);

            $data = [
                'id' => $var['notification_id'],
                'alliance_id' => $text['allianceID'],
                'item_id' => $text['typeID'],
                'timestamp' => $time,
                'tower_status_id' => 1,
                'user_id' => null,

            ];
            Tower::updateOrCreate($moon_id, $data);
            // $check = Tower::where('moon_id', $moon_id)->first();
            // if ($check == null) {
            //     Tower::updateOrCreate($moon_id, $data);
            // } else {

            //     if ($var['notification_id'] > $towernumber) {

            //         Tower::updateOrCreate($moon_id, $data);
            //     }
            // }
            // }
        } elseif ($var['type'] == 'StructureUnderAttack') {
            if ($var['notification_id'] > $maxNotificationID) {
                $station = Station::where('id', $text['structureID'])->first();
                if ($station == null) {
                    $stationdata = reconPull($text['structureID']);
                    if (array_key_exists('str_structure_id_md5', $stationdata)) {
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
                        Station::Create([
                            'id' => $text['structureID'],
                            'name' => $stationdata['str_name'],
                            'standing' => $standing,
                            'corp_id' => $stationdata['str_owner_corporation_id'],
                            'system_id' => $stationdata['str_system_id'],
                            'item_id' => $stationdata['str_type_id'],
                            'text' => null,
                            'user_id' => null,
                            'station_status_id' => 1,
                            'timestamp' => $time,
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
                            'status_update' => $current,
                            'show_on_main' => 1,
                            'added_from_recon' => 1,
                        ]);
                        if ($stationdata['str_has_no_fitting'] != null) {
                            $items = Utils::jsonDecode($stationdata['str_fitting'], true);
                            foreach ($items as $item) {
                                StationItems::where('id', $item['type_id'])->get()->count();
                                if (StationItems::where('id', $item['type_id'])->get()->count() == 0) {
                                    StationItems::Create(['id' => $item['type_id'], 'item_name' => $item['name']]);
                                }
                                StationItemJoin::create(['station_item_id' => $item['type_id'], 'station_id' => $text['structureID']]);
                            }
                        }
                    } else {
                        Station::Create([
                            'id' => $text['structureID'],
                            'name' => $stationdata['name'],
                            'system_id' => $stationdata['solar_system_id'],
                            'item_id' => $stationdata['type_id'],
                            'corp_id' => $stationdata['owner_id'],
                            'text' => null,
                            'user_id' => null,
                            'station_status_id' => 1,
                            'timestamp' => $time,
                            'status_update' => $current,
                            'out_time' => null,
                            'show_on_main' => 1,
                        ]);
                    }
                } else {
                    if ($station->station_status_id == 6 || $station->station_status_id == 10) {
                        $status = 1;
                    } else {
                        $status = $station->station_status_id;
                    }
                    $station->update([
                        'text' => null,
                        'user_id' => null,
                        'station_status_id' => $status,
                        'timestamp' => $time,
                        'status_update' => $current,
                        'show_on_main' => 1,
                    ]);
                }

                $data = [
                    'id' => $var['notification_id'],
                    'timestamp' => $time,
                ];
                StationNotification::updateOrCreate($station_id, $data);
            }


            $message = stationRecordSolo($text['structureID']);
            $flag = null;
            $flag = collect([
                'message' => $message,
            ]);
            broadcast(new StationNotificationNew($flag));
            broadcast(new StationInfoSet($flag));
        } elseif ($var['type'] == 'StructureLostShields') {
            $outTime = null;
            $ldap = $text['timestamp'];
            $winSecs = (int) ($ldap / 10000000);
            $unixTimestamp = ($winSecs - 11644473600);
            $outTime = date('Y-m-d H:i:s', $unixTimestamp);

            if ($var['notification_id'] > $maxNotificationID) {
                $station = Station::where('id', $text['structureID'])->first();
                if ($station == null) {
                    $stationdata = reconPull($text['structureID']);
                    if (array_key_exists('str_structure_id_md5', $stationdata)) {
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
                        Station::Create([
                            'id' => $text['structureID'],
                            'name' => $stationdata['str_name'],
                            'standing' => $standing,
                            'system_id' => $stationdata['str_system_id'],
                            'corp_id' => $stationdata['str_owner_corporation_id'],
                            'item_id' => $stationdata['str_type_id'],
                            'text' => null,
                            'user_id' => null,
                            'station_status_id' => 8,
                            'timestamp' => $time,
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
                            'status_update' => $current,
                            'out_time' => $outTime,
                            'show_on_main' => 1,
                            'added_from_recon' => 1,
                        ]);
                        if ($stationdata['str_has_no_fitting'] != null) {
                            $items = Utils::jsonDecode($stationdata['str_fitting'], true);
                            foreach ($items as $item) {
                                StationItems::where('id', $item['type_id'])->get()->count();
                                if (StationItems::where('id', $item['type_id'])->get()->count() == 0) {
                                    StationItems::Create(['id' => $item['type_id'], 'item_name' => $item['name']]);
                                }
                                StationItemJoin::create(['station_item_id' => $item['type_id'], 'station_id' => $text['structureID']]);
                            }
                        }
                    } else {
                        Station::Create([
                            'id' => $text['structureID'],
                            'name' => $stationdata['name'],
                            'system_id' => $stationdata['solar_system_id'],
                            'item_id' => $stationdata['type_id'],
                            'corp_id' => $stationdata['owner_id'],
                            'text' => null,
                            'user_id' => null,
                            'station_status_id' => 8,
                            'timestamp' => $time,
                            'out_time' => $outTime,
                            'status_update' => $current,
                            'show_on_main' => 1,
                        ]);
                    }
                } else {
                    $station->update([
                        'text' => null,
                        'user_id' => null,
                        'station_status_id' => 8,
                        'timestamp' => $time,
                        'status_update' => $current,
                        'out_time' => $outTime,
                        'show_on_main' => 1,
                    ]);
                }

                $data = [
                    'id' => $var['notification_id'],
                    'timestamp' => $time,

                ];
                StationNotificationShield::updateOrCreate($station_id, $data);
            }
            $message = stationRecordSolo($text['structureID']);
            $flag = null;
            $flag = collect([
                'message' => $message,
            ]);
            broadcast(new StationNotificationNew($flag));
            broadcast(new StationInfoSet($flag));
        } elseif ($var['type'] == 'StructureLostArmor') {
            $outTime = null;
            $ldap = $text['timestamp'];
            $winSecs = (int) ($ldap / 10000000);
            $unixTimestamp = ($winSecs - 11644473600);
            $outTime = date('Y-m-d H:i:s', $unixTimestamp);

            if ($var['notification_id'] > $maxNotificationID) {
                $station = Station::where('id', $text['structureID'])->first();
                // echo $stationcheck;
                if ($station == null) {
                    $stationdata = reconPull($text['structureID']);
                    if (array_key_exists('str_structure_id_md5', $stationdata)) {
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
                        Station::Create([
                            'id' => $text['structureID'],
                            'name' => $stationdata['str_name'],
                            'standing' => $standing,
                            'system_id' => $stationdata['str_system_id'],
                            'item_id' => $stationdata['str_type_id'],
                            'text' => null,
                            'corp_id' => $stationdata['str_owner_corporation_id'],
                            'user_id' => null,
                            'station_status_id' => 9,
                            'timestamp' => $time,
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
                            'status_update' => $current,
                            'out_time' => $outTime,
                            'show_on_main' => 1,
                            'added_from_recon' => 1,
                        ]);
                        if ($stationdata['str_has_no_fitting'] != null) {
                            $items = Utils::jsonDecode($stationdata['str_fitting'], true);
                            foreach ($items as $item) {
                                StationItems::where('id', $item['type_id'])->get()->count();
                                if (StationItems::where('id', $item['type_id'])->get()->count() == 0) {
                                    StationItems::Create(['id' => $item['type_id'], 'item_name' => $item['name']]);
                                }
                                StationItemJoin::create(['station_item_id' => $item['type_id'], 'station_id' => $text['structureID']]);
                            }
                        }
                    } else {
                        Station::Create([
                            'id' => $text['structureID'],
                            'name' => $stationdata['name'],
                            'system_id' => $stationdata['solar_system_id'],
                            'item_id' => $stationdata['type_id'],
                            'text' => null,
                            'corp_id' => $stationdata['owner_id'],
                            'user_id' => null,
                            'station_status_id' => 9,
                            'timestamp' => $time,
                            'out_time' => $outTime,
                            'status_update' => $current,
                            'show_on_main' => 1,
                        ]);
                    }
                } else {
                    $station->update([
                        'text' => null,
                        'user_id' => null,
                        'station_status_id' => 9,
                        'timestamp' => $time,
                        'status_update' => $current,
                        'out_time' => $outTime,
                        'show_on_main' => 1,
                    ]);

                    $data = [
                        'id' => $var['notification_id'],
                        'timestamp' => $time,

                    ];
                    StationNotificationArmor::updateOrCreate($station_id, $data);
                }
            }
            $message = stationRecordSolo($text['structureID']);

            $flag = null;
            $flag = collect([
                'message' => $message,
            ]);
            broadcast(new StationNotificationNew($flag));
            broadcast(new StationInfoSet($flag));
        }
    }
}

if (!function_exists('notificationUpdate')) {
    function notificationUpdate($data)
    {
        $current = now();
        $now = $current->modify('-10 minutes');
        $stationflag = 0;
        $towerflag = 0;
        $flag = 0;

        $check = Notification::where('status_id', 2)
            ->where('timestamp', '<=', $now)
            ->count();
        if ($check > 0) {
            $n = Notification::where('status_id', 2)
                ->where('timestamp', '<=', $now)->get();

            foreach ($n as $n) {
                $n->update(['status_id' => 10]);
            }
            $flag = 1;
            $check = null;
        }

        $check = Notification::where('status_id', 4)
            ->where('timestamp', '<=', $now)
            ->count();

        if ($check > 0) {
            $n = Notification::where('status_id', 4)
                ->where('timestamp', '<=', $now)->get();
            foreach ($n as $n) {
                $n->update(['status_id' => 10]);
            }
            $flag = 1;
            $check = null;
        }

        $notenumber = Notification::max('id');
        $tempnumber = Temp_notifcation::max('id');
        foreach ($data as $var) {

            //dwdwdw
            if ($var['type'] == 'EntosisCaptureStarted') {
                if ($var['notification_id'] > $notenumber) {

                    //dd($time2);
                    $time = $var['timestamp'];

                    $time = fixtime($time);
                    $result = [];
                    $data = [];
                    $text = $var['text'];
                    $text = explode("\n", $text);
                    $text = str_replace('solarSystemID', 'system_id', $text);
                    $text = str_replace('structureTypeID', 'item_id', $text);
                    array_pop($text);

                    for ($i = 0; $i < count($text); $i++) {
                        $lines = $text;
                        $keys = explode(':', $lines[$i]);
                        $item = $keys;
                        array_pop($keys);
                        unset($item[0]);
                        $item = array_map('trim', $item);
                        $item[1] = (int) $item[1];
                        $item = array_values($item);
                        $result[$keys[0]] = $item[0];
                    }

                    $si_id = $result['system_id'] . $result['item_id'];
                    $check_si_id = $si_id;
                    $check_si_id = (int) $check_si_id;
                    $si_id = [
                        'si_id' => $si_id = (int) $si_id,
                    ];

                    $data = [
                        'id' => $var['notification_id'],
                        'timestamp' => $time,
                        'notification_type_id' => 1,
                        'status_id' => 1,
                        'user_id' => null,

                    ];
                    $data2 = array_merge($data, $result);
                    $check = Notification::where('si_id', $check_si_id)->first();
                    $count = Notification::where('si_id', $check_si_id)->get()->count();
                    if ($count == 0) {
                        Notification::updateOrCreate($si_id, $data2);
                        $flag = 1;
                    } else {
                        if ($var['notification_id'] > $check->id) {
                            Notification::updateOrCreate($si_id, $data2);
                            $flag = 1;
                        }
                    }
                }
            } elseif ($var['type'] == 'SovStructureReinforced') {
                if ($var['notification_id'] > $tempnumber) {
                    $time2 = $var['timestamp'];
                    $time = $var['timestamp'];
                    $time = fixtime($time);
                    $result = [];
                    $data = [];

                    $text = $var['text'];
                    $text = explode("\n", $text);
                    $text = str_replace('campaignEventType', 'event_type_id', $text);
                    $text = str_replace('solarSystemID', 'system_id', $text);
                    array_pop($text);

                    for ($i = 0; $i < count($text); $i++) {
                        $lines = $text;
                        $keys = explode(':', $lines[$i]);
                        if ($keys[0] !== 'decloakTime') {
                            $item = $keys;
                            array_pop($keys);
                            unset($item[0]);
                            $item = array_map('trim', $item);
                            $item[1] = (int) $item[1];
                            $item = array_values($item);
                            $result[$keys[0]] = $item[0];
                        }
                    }

                    $es_id = $result['event_type_id'] . $result['system_id'];
                    $check_es_id = $es_id;
                    $check_es_id = (int) $check_es_id;
                    $es_id = [
                        'es_id' => $es_id = (int) $es_id,
                    ];
                    $data = [
                        'id' => $var['notification_id'],
                        'timestamp' => $time,
                        'notification_type_id' => 2,
                        'status' => 0,
                    ];

                    // ($data2);
                    $data2 = array_merge($data, $result);
                    $check = Temp_notifcation::where('es_id', $check_es_id)->first();
                    $count = Temp_notifcation::where('es_id', $check_es_id)->get()->count();
                    if ($count == 0) {
                        Temp_notifcation::updateOrCreate($es_id, $data2);
                        $flag = 1;
                    } else {
                        if ($var['notification_id'] > $check->id) {
                            Temp_notifcation::updateOrCreate($es_id, $data2);
                            $flag = 1;
                        }
                    }
                }
            }
        }

        $tempnote = Temp_notifcation::where('status', 0)->get();
        foreach ($tempnote as $tempnote) {
            if ($tempnote->event_type_id == 1) {
                $stype = 32226;
            } else {
                $stype = 32458;
            }

            $si_id = $tempnote->system_id . $stype;
            $si_id = (int) $si_id;
            $check = Notification::where('si_id', $si_id)->get();
            if ($check->count() == 1) {
                $check = $check->toArray();

                if ($tempnote->id > $check[0]['id']) {
                    $n = Notification::where('si_id', $si_id)
                        ->where('item_id', $stype)->get();

                    foreach ($n as $n) {
                        $n->update([
                            'status_id' => 2,
                            'user_id' => null,
                        ]);
                    }
                }
                $t = Temp_notifcation::where('id', $tempnote->id)->get();
                foreach ($t as $t) {
                    $t->update(['status' => 1]);
                }
            } else {
                $t = Temp_notifcation::where('id', $tempnote->id)->get();
                foreach ($t as $t) {
                    $t->update(['status' => 1]);
                }
            }
        }

        return $request = [
            'stationflag' => $stationflag,
            'towerflag' => $towerflag,
            'notificationflag' => $flag,
        ];
    }
}

if (!function_exists('getAllNotification')) {
    function getAllNotification()
    {

        $notifications = Notification::whereNot('status_id', 10)
            ->with([
                'system:id,constellation_id,region_id,system_name',
                'system.constellation:id,constellation_name',
                'system.region:id,region_name',
                'item:id,item_name',
                'notification_type:id,name',
                'status:id,name',
                'user:id,name',
                'dscan',
                'dscan.system:id,region_id,constellation_id,system_name',
                'dscan.system.region',
                'dscan.system.constellation',
                'dscan.updatedBy:id,name',
                'dscan.madeby:id,name',
                'dscan.messages.user',
                'dscan.locals.corp.alliance.affiliation',
                'dscan.history:dscan_id,link,history_count,created_at,corpsTotalNumber,alliancesTotalNumber,affiliationsTotalNumber,itemTotalsNumber,groupTotalsNumber,categoryTotalsNumber'
            ])
            ->get();

        return $notifications;
    }
}

if (!function_exists('getSoloNotification')) {
    function getSoloNotification($id)
    {

        $notifications = Notification::whereNot('status_id', 10)
            ->whereId($id)
            ->with([
                'system:id,constellation_id,region_id,system_name',
                'system.constellation:id,constellation_name',
                'system.region:id,region_name',
                'item:id,item_name',
                'notification_type:id,name',
                'status:id,name',
                'user:id,name',
                'dscan',
                'dscan.system:id,region_id,constellation_id,system_name',
                'dscan.system.region',
                'dscan.system.constellation',
                'dscan.updatedBy:id,name',
                'dscan.madeby:id,name',
                'dscan.messages.user',
                'dscan.locals.corp.alliance.affiliation',
                'dscan.history:dscan_id,link,history_count,created_at,corpsTotalNumber,alliancesTotalNumber,affiliationsTotalNumber,itemTotalsNumber,groupTotalsNumber,categoryTotalsNumber'
            ])
            ->first();

        return $notifications;
    }
}



if (!function_exists('stationNotificationCheck')) {
    function stationNotificationCheck()
    {
        // $now = now();
        // $now5min = now()->subMinutes(5);
        // $now10min = now()->subMinutes(10);
        // $now20min = now()->subMinutes(20);
        // $now30min = now()->subMinutes(30);
        // $now1hour = now()->subHours(1);
        // $now5hour = now()->subHours(5); //if less than
        // $soon24hour = now()->addDay();

        // $checks = Station::where('status_update', '<', $now5hour)
        //     ->where('station_status_id', 1)
        //     ->where('show_on_rc', 0)
        //     ->where('show_on_rc_move', 0)
        //     ->where('show_on_coord', 0)
        //     ->where('show_on_chill', 0)
        //     ->where('show_on_welp', 0)
        //     ->get(); //New
        // foreach ($checks as $check) {
        //     $check->update([
        //         'station_status_id' => 10,
        //         'user_id' => null,
        //         'text' => null,
        //         'gunner_id' => null,
        //         'out_time' => null
        //     ]);
        //     $stationID = $check->id;
        //     $flag = null;
        //     $flag = collect([
        //         'id' => $check->id,
        //     ]);
        //     broadcast(new StationNotificationDelete($flag));
        // }

        // $checks = Station::where('status_update', '<', $now5hour)
        //     ->where('station_status_id', 2)
        //     ->where('show_on_rc', 0)
        //     ->where('show_on_rc_move', 0)
        //     ->where('show_on_coord', 0)
        //     ->where('show_on_chill', 0)
        //     ->where('show_on_welp', 0)
        //     ->get(); //On They way
        // foreach ($checks as $check) {
        //     $check->update([
        //         'station_status_id' => 10,
        //         'user_id' => null,
        //         'text' => null,
        //         'gunner_id' => null,
        //         'out_time' => null
        //     ]);
        //     $stationID = $check->id;
        //     $flag = null;
        //     $flag = collect([
        //         'id' => $check->id,
        //     ]);
        //     broadcast(new StationNotificationDelete($flag));
        // }

        // $checks = Station::where('status_update', '<', $now5hour)->where('station_status_id', 3)
        //     ->where('show_on_rc', 0)
        //     ->where('show_on_rc_move', 0)
        //     ->where('show_on_coord', 0)
        //     ->where('show_on_chill', 0)
        //     ->where('show_on_welp', 0)
        //     ->get(); //Gunning
        // foreach ($checks as $check) {
        //     $check->update([
        //         'station_status_id' => 10,
        //         'user_id' => null,
        //         'text' => null,
        //         'gunner_id' => null,
        //         'out_time' => null
        //     ]);
        //     $stationID = $check->id;
        //     $flag = null;
        //     $flag = collect([
        //         'id' => $check->id,
        //     ]);
        //     broadcast(new StationNotificationDelete($flag));
        // }

        // $checks = Station::where('status_update', '<', $now10min)
        //     ->where('station_status_id', 4)
        //     ->where('show_on_rc', 0)
        //     ->where('show_on_rc_move', 0)
        //     ->where('show_on_coord', 0)
        //     ->where('show_on_chill', 0)
        //     ->where('show_on_welp', 0)
        //     ->get(); //Saved
        // foreach ($checks as $check) {
        //     $check->update(['station_status_id' => 10, 'user_id' => null, 'text' => null, 'gunner_id' => null, 'out_time' => null]);
        //     $stationID = $check->id;
        //     $flag = null;
        //     $flag = collect([
        //         'id' => $check->id,
        //     ]);
        //     broadcast(new StationNotificationDelete($flag));
        // }

        // $checks = Station::where('out_time', '<=', $now)
        //     ->where('station_status_id', 5)
        //     ->where('show_on_rc', 0)
        //     ->where('show_on_rc_move', 0)
        //     ->where('show_on_coord', 0)
        //     ->where('show_on_chill', 0)
        //     ->where('show_on_welp', 0)
        //     ->get(); //Upcoming
        // foreach ($checks as $check) {
        //     $check->update([
        //         'station_status_id' => 6,
        //         'status_update' => now(),
        //         'out_time' => null,
        //         'timestamp' => now()
        //     ]);

        //     $message = stationRecordSolo($check->id);
        //     $flag = null;
        //     $flag = collect([
        //         'message' => $message,
        //     ]);
        //     broadcast(new StationNotificationUpdate($flag));
        // }

        // $checks = Station::where('out_time', '<=', $now)
        //     ->where('station_status_id', 13)
        //     ->where('show_on_rc', 0)
        //     ->where('show_on_rc_move', 0)
        //     ->where('show_on_coord', 0)
        //     ->where('show_on_chill', 0)
        //     ->where('show_on_welp', 0)
        //     ->get(); //Upcoming
        // foreach ($checks as $check) {
        //     $check->update([
        //         'station_status_id' => 6,
        //         'status_update' => now(),
        //         'out_time' => null,
        //         'timestamp' => now()
        //     ]);
        //     $message = stationRecordSolo($check->id);
        //     $flag = null;
        //     $flag = collect([
        //         'message' => $message,
        //     ]);
        //     broadcast(new StationNotificationUpdate($flag));
        // }

        // $checks = Station::where('out_time', '<=', $now)
        //     ->where('station_status_id', 14)
        //     ->where('show_on_rc', 0)
        //     ->where('show_on_rc_move', 0)
        //     ->where('show_on_coord', 0)
        //     ->where('show_on_chill', 0)
        //     ->where('show_on_welp', 0)
        //     ->get(); //Upcoming
        // foreach ($checks as $check) {
        //     $check->update([
        //         'station_status_id' => 15,
        //         'status_update' => now(),
        //         'out_time' => null,
        //         'timestamp' => now()
        //     ]);

        //     $message = stationRecordSolo($check->id);
        //     $flag = null;
        //     $flag = collect([
        //         'message' => $message,
        //     ]);
        //     broadcast(new StationNotificationUpdate($flag));
        // }

        // $checks = Station::where('status_update', '<', $now5hour)
        //     ->where('station_status_id', 6)
        //     ->where('show_on_rc', 0)
        //     ->where('show_on_rc_move', 0)
        //     ->where('show_on_coord', 0)
        //     ->where('show_on_chill', 0)
        //     ->where('show_on_welp', 0)
        //     ->get(); //Out
        // foreach ($checks as $check) {
        //     $check->update([
        //         'station_status_id' => 10,
        //         'user_id' => null,
        //         'text' => null,
        //         'gunner_id' => null,
        //         'out_time' => null
        //     ]);
        //     $stationID = $check->id;
        //     $flag = null;
        //     $flag = collect([
        //         'id' => $check->id,
        //     ]);
        //     broadcast(new StationNotificationDelete($flag));
        // }

        // $checks = Station::where('out_time', '<', $now10min)
        //     ->where('station_status_id', 15)
        //     ->where('show_on_rc', 0)
        //     ->where('show_on_rc_move', 0)
        //     ->where('show_on_coord', 0)
        //     ->where('show_on_chill', 0)
        //     ->where('show_on_welp', 0)
        //     ->get(); //Out
        // foreach ($checks as $check) {
        //     $check->update([
        //         'station_status_id' => 10,
        //         'user_id' => null,
        //         'text' => null,
        //         'gunner_id' => null,
        //         'out_time' => null
        //     ]);
        //     $stationID = $check->id;
        //     $flag = null;
        //     $flag = collect([
        //         'id' => $check->id,
        //     ]);
        //     broadcast(new StationNotificationDelete($flag));
        // }

        // $checks = Station::where('status_update', '<', $now10min)
        //     ->where('station_status_id', 7)
        //     ->where('show_on_rc', 0)
        //     ->where('show_on_rc_move', 0)
        //     ->where('show_on_coord', 0)
        //     ->where('show_on_chill', 0)
        //     ->where('show_on_welp', 0)
        //     ->get(); //Destoryed
        // foreach ($checks as $check) {
        //     $stationID = $check->id;
        //     $flag = null;
        //     $flag = collect([
        //         'id' => $check->id,
        //     ]);
        //     broadcast(new StationNotificationDelete($flag));
        //     $s = StationItemJoin::where('station_id', $check->id)->get();
        //     foreach ($s as $s) {
        //         $s->delete();
        //     }
        //     $s = StationNotificationArmor::where('station_id', $check->id)->get();
        //     foreach ($s as $s) {
        //         $s->delete();
        //     }
        //     $s = StationNotificationShield::where('station_id', $check->id)->get();
        //     foreach ($s as $s) {
        //         $s->delete();
        //     }
        //     $s = StationNotification::where('station_id', $check->id)->get();
        //     foreach ($s as $s) {
        //         $s->delete();
        //     }
        //     $check->delete();
        // }

        // $checks = Station::where('status_update', '<', $now10min)
        //     ->where('station_status_id', 8)
        //     ->where('show_on_rc', 0)
        //     ->where('show_on_rc_move', 0)
        //     ->where('show_on_coord', 0)
        //     ->where('show_on_chill', 0)
        //     ->where('show_on_welp', 0)
        //     ->get(); //Reffed - Shield
        // foreach ($checks as $check) {
        //     $check->update([
        //         'station_status_id' => 5,
        //         'user_id' => null,
        //         'text' => null,
        //         'gunner_id' => null,
        //         'status_update' => now(),
        //         'gunner_id' => null,
        //         'attack_notes' => null,
        //         'attack_adash_link' => null
        //     ]);
        //     $stationID = $check->id;

        //     $message = stationRecordSolo($check->id);
        //     $flag = null;
        //     $flag = collect([
        //         'message' => $message,
        //     ]);
        //     broadcast(new StationNotificationNew($flag));
        // }

        // $checks = Station::where('status_update', '<', $now10min)
        //     ->where('station_status_id', 9)
        //     ->where('show_on_rc', 0)
        //     ->where('show_on_rc_move', 0)
        //     ->where('show_on_coord', 0)
        //     ->where('show_on_chill', 0)
        //     ->where('show_on_welp', 0)
        //     ->get(); //Reffed - Armor
        // foreach ($checks as $check) {
        //     $check->update([
        //         'station_status_id' => 13, 'user_id' => null,
        //         'text' => null,
        //         'gunner_id' => null,
        //         'status_update' => now(),
        //         'gunner_id' => null,
        //         'attack_notes' => null,
        //         'attack_adash_link' => null
        //     ]);

        //     $message = stationRecordSolo($check->id);
        //     $flag = null;
        //     $flag = collect([
        //         'message' => $message,
        //     ]);
        //     broadcast(new StationNotificationNew($flag));
        // }
    }
}
if (!function_exists('towerUpdate')) {
    function towerUpdate()
    {
        $now10min = now()->modify(' -10 minutes');
        $now10min = now()->subMinutes(10);
        $towers = Tower::where('tower_status_id', 6)->where('updated_at', '<', $now10min)->get();
        foreach ($towers as $tower) {
            $id = $tower->id;
            $flag = null;
            $flag = collect([
                'id' => $id,
            ]);
            broadcast(new TowerDelete($flag));
            $tower->delete();
        }

        $towers = Tower::where('tower_status_id', 3)->where('out_time', '>', now())->get();
        foreach ($towers as $tower) {
            $tower->update(['tower_status_id' => 4, 'out_time' => null]);
            // $message = TowerRecord::where('id', $tower->id)->first();
            // if ($message->status_id != 10) {
            //     $flag = collect([
            //         'message' => $message,
            //     ]);
            //     broadcast(new TowerChanged($flag));
            // }
        }
        $towers = Tower::where('tower_status_id', 5)->where('out_time', '<', now())->get();
        foreach ($towers as $tower) {
            if ($tower->out_time != null) {
                $tower->update(['tower_status_id' => 8]);
                // $message = TowerRecord::where('id', $tower->id)->first();
                // if ($message->status_id != 10) {
                //     $flag = collect([
                //         'message' => $message,
                //     ]);
                //     broadcast(new TowerChanged($flag));
                // }
            }
        }
    }
}
