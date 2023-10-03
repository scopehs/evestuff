<?php

use App\Events\StationWatchListSettingPageUpdate;
use App\Models\Auth;
use App\Models\Campaign;
use App\Models\Client;
use App\Models\Station;
use App\Models\User;
use GuzzleHttp\Client as GuzzleHttpClient;
use GuzzleHttp\Utils;
use Illuminate\Support\Facades\Auth as FacadesAuth;
use Illuminate\Support\Facades\Http;

if (!function_exists('displayName')) {
    function displayName()
    {
        return 'Laravel fefefFramework';
    }
}

if (!function_exists('sendStationListUpdateToWatchListPage')) {
    function sendStationListUpdateToWatchListPage($id)
    {
        $stationList = Station::whereId($id)
            ->orderBy('name', 'asc')
            ->select(['name as text', 'stations.id as value'])
            ->first();

        $flag = collect([
            'flag' => 2,
            'message' => $stationList,
        ]);
        broadcast(new StationWatchListSettingPageUpdate($flag));
        $data = getGtationWatchListNeededInfo();
        $flag = collect([
            'flag' => 1,
            'message' => $data,
        ]);
        broadcast(new StationWatchListSettingPageUpdate($flag));
    }
}


if (!function_exists('checkPermissions')) {
    function checkPermissions()
    {
        $array =
            [
                'roles' => FacadesAuth::user()->roles->pluck('name'),
                'permissions' => FacadesAuth::user()->allPermissions,
            ];

        return json_encode($array);
    }
}
if (!function_exists('authcheck')) {
    function authcheck()
    {
        $auths = Auth::all();
        foreach ($auths as $auth) {
            // echo " - " . $auth->name . " - ";

            $expire_date = new DateTime($auth->expire_date);
            $date = new DateTime();

            if ($date > $expire_date) {
                // if (1 > 0) {
                // echo "old!¬¬¬¬¬ !!!! ----";
                $client = Client::first();
                $http = new GuzzleHttpCLient();

                $headers = [
                    'Authorization' => 'Basic ' . $client->code,
                    'Content-Type' => 'application/x-www-form-urlencoded',
                    'Host' => 'login.eveonline.com',
                    'User-Agent' => 'evestuff.online evestuff@lol.com',

                ];
                $body = 'grant_type=refresh_token&refresh_token=' . $auth->refresh_token;
                // echo $body;
                $response = $http->request('POST', 'https://login.eveonline.com/v2/oauth/token', [
                    'headers' => $headers,
                    'body' => $body,
                ]);
                $data = Utils::jsonDecode($response->getBody(), true);
                // dd($data);
                $date = new DateTime();
                $date = $date->modify('+19 minutes');
                $auth->update(['access_token' => $data['access_token'], 'refresh_token' => $data['refresh_token'], 'expire_date' => $date]);
            }
        }
    }
}
if (!function_exists('authpull')) {
    function authpull($type, $station_id)
    {
        $type = $type;

        if ($type == 'standing') {
            $token = Auth::where('flag_standing', 0)->first();
            // dd($token);
            // echo "auth pull - ";
            if ($token == null) {
                $a = Auth::where('flag_standing', 1)->get();
                foreach ($a as $a) {
                    $a->update(['flag_standing' => 0]);
                }
                $token = Auth::where('flag_standing', 0)->first();
                $token->update(['flag_note' => 1]);
                $url = 'https://esi.evetech.net/latest/alliances/1354830081/contacts/?datasource=tranquility';
            } else {
                $token->update(['flag_standing' => 1]);
                $url = 'https://esi.evetech.net/latest/alliances/1354830081/contacts/?datasource=tranquility';
            }
        } elseif ($type == 'note') {
            $token = Auth::where('flag_note', 0)->first();

            if ($token == null) {
                // echo "yo yo yo";
                Auth::where('flag_note', 1)->update(['flag_note' => 0]);
                $token = Auth::where('flag_note', 0)->first();
                $token->update(['flag_note' => 1]);

                $url = 'https://esi.evetech.net/latest/characters/' . $token->char_id . '/notifications/';
                // dd($url);
            } else {
                $token->update(['flag_note' => 1]);
                $url = 'https://esi.evetech.net/latest/characters/' . $token->char_id . '/notifications/';
                // dd($url);
            }
        } elseif ($type == 'station') {
            $token = Auth::where('flag_station', 0)->first();

            if ($token == null) {
                $a = Auth::where('flag_station', 1)->get();
                foreach ($a as $a) {
                    $a->update(['flag_station' => 0]);
                }
                $token = Auth::where('flag_station', 0)->first();
                $token->update(['flag_station' => 1]);

                $url = 'https://esi.evetech.net/latest/universe/structures/' . $station_id . '/?datasource=tranquility';
                // dd($url);
            } else {
                $token->update(['flag_station' => 1]);
                $url = 'https://esi.evetech.net/latest/universe/structures/' . $station_id . '/?datasource=tranquility';
                // dd($url);
            }
        }
        // echo $url.":url";
        $client = new GuzzleHttpClient();
        $headers = [
            'Authorization' => 'Bearer ' . $token->access_token,
            'User-Agent' => 'evestuff.online evestuff@lol.com',

        ];
        $good = 0;

        while ($good == 0) {
            $response = $client->request('GET', $url, [
                'headers' => $headers,
                'http_errors' => false,
            ]);
            if ($response->getStatusCode() == 200) {
                $data = Utils::jsonDecode($response->getBody(), true);
                $good = 1;

                return $data;
            } else {
                sleep(10);
            }
        }
    }
}
if (!function_exists('fixtime')) {
    function fixtime($time)
    {
        $time = str_replace('Z', '', $time);
        $time = str_replace('T', ' ', $time);
        $time = str_replace('+00:00', '', $time);

        return $time;
    }
}
if (!function_exists('clearRemember')) {
    function clearRemember()
    {

        $now = now()->subDays(3);
        $u = User::where('updated_at', '<', $now)->get();
        foreach ($u as $u) {
            $u->update(['remember_token' => null]);
        }
    }
}
if (!function_exists('checkeve')) {
    function checkeve()
    {
        $http = new GuzzleHttpCLient();

        $headers = [
            'Accept' => 'text/plain',
            'User-Agent' => 'evestuff.online evestuff@lol.com',
        ];

        $response = $http->request('GET', 'https://esi.evetech.net/ping');
        $status = $response->getBody();
        if ($status != 'ok') {
            return 0;
        }

        return 1;
    }
}
if (!function_exists('eveUserCount')) {
    function eveUserCount()
    {
        $http = new GuzzleHttpCLient();
        $headers = [
            'Accept' => 'application/json',
            'User-Agent' => 'evestuff.online evestuff@lol.com',
        ];

        $response = $http->request('GET', 'https://esi.evetech.net/latest/status/?datasource=tranquility', [
            'headers' => $headers,
        ]);
        $status = Utils::jsonDecode($response->getBody());

        return $status->players;
    }
}
if (!function_exists('campaignName')) {
    function campaignName($campaignID)
    {
        $campaign = Campaign::where('id', $campaignID)->first();
        $systemname = $campaign->system->system_name;
        if ($campaign->event_type == 32226) {
            $itemname = 'TCU';
        } else {
            $itemname = 'IHUB';
        }
        $campaignname = $itemname . ' in ' . $systemname;

        return $date = [
            'campaign_name' => $campaignname,
            'system_name' => $systemname,
        ];
    }
}
if (!function_exists('logUpdate')) {
    function logUpdate($campid, $log)
    {
    }
}
if (!function_exists('sheetlogs')) {
    function sheetlogs($log)
    {
    }
}
if (!function_exists('stationlogs')) {
    function stationlogs($log)
    {
    }
}
if (!function_exists('StationRecords')) {
    function StationRecords($type, $ids)
    {

        $user = FacadesAuth::user();
        $type = $type;
        $station_query = Station::query();
        if ($type == 1) {
            $station_query->where('show_on_main', 1);
        }

        if ($type == 4) {
            $station_query->where('show_on_rc', 1);
            if ($user->can('use_reserved_connection')) {
                $station_query->with(['system.webway' => function ($t) {
                    $t->where('permissions', 1);
                }]);
            } else {
                $station_query->with(['system.webway' => function ($t) {
                    $t->where('permissions', 0);
                }]);
            }
        }

        if ($type == 5) {
            $station_query->where('show_on_rc_move', 1);
        }

        if ($type == 6) {

            if ($user->can('view_station_logs')) {
                $station_query->with([
                    'logs.causer:id,name'
                ]);
            }
        }
        $station_query->whereIn('id', $ids)->with(['system.webway' => function ($t) {
            $t->where('permissions', 1);
        }]);
        $station_query->where('standing', '=<', 0);
        $station_query->with([
            'system',
            'system.constellation',
            'system.region',
            'status:id,name',
            'fc.user:id,name',
            'recon.user:id,name',
            'gsoluser.user:id,name',
            'corp:id,alliance_id,name,ticker,standing,url,color',
            'corp.alliance:id,name,ticker,standing,url,color',
            'item',
            'fit',
            'addedBy:id,name',
            'notes.user',

        ]);

        $stationRecords = $station_query->get();
        $stationRecords->each->append('list');

        return $stationRecords;
    }
}
if (!function_exists('StationRecordsSolo')) {
    function StationRecordsSolo($type, $id)
    {
        $user = FacadesAuth::user();
        $type = $type;
        $station_query = Station::query();

        if ($type == 1) {
            $station_query->where('show_on_main', 1);
        }

        //rchsheet
        if ($type == 4) {
            $station_query->where('show_on_rc', 1);
            if ($user->can('use_reserved_connection')) {
                $station_query->with(['system.webway' => function ($t) {
                    $t->where('permissions', 1);
                }]);
            } else {
                $station_query->with(['system.webway' => function ($t) {
                    $t->where('permissions', 0);
                }]);
            }
        }

        if ($type == 5) {
        }

        //station sheet
        if ($type == 6) {
            if ($user->can('view_station_logs')) {
                $station_query->with([
                    'logs.causer:id,name'
                ]);
            }
        }

        $station_query->where('standing', '=<', 0);
        $station_query->where('id', $id)->with(['system.webway' => function ($t) {
            $t->where('permissions', 1);
        }]);;
        $station_query->with([
            'system',
            'system.constellation',
            'system.region',
            'status:id,name',
            'fc.user:id,name',
            'recon.user:id,name',
            'gsoluser.user:id,name',
            'corp:id,alliance_id,name,ticker,standing,url,color',
            'corp.alliance:id,name,ticker,standing,url,color',
            'item',
            'fit',
            'notes.user',
            'addedBy:id,name',
        ]);

        $stationRecords = $station_query->first();
        if ($stationRecords) {

            $stationRecords->append('list');
            return $stationRecords;
        }
        return null;
    }
}

if (!function_exists('nameToID')) {
    function nameToID($name)
    {
        $userIds = collect();
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'User-Agent' => 'evestuff.online evestuff@lol.com',
        ])
            ->withBody(json_encode([$name]), 'application/json')
            ->post('https://esi.evetech.net/latest/universe/ids/?datasource=tranquility&language=en');

        $res = $response->collect($key = null);
        foreach ($res as $key => $re) {
            if ($key == 'characters') {
                $userIds->push($re[0]['id']);
            }
        }
        $count = $userIds->count();
        if ($count > 0) {
            return $userIds[0];
        } else {
            return null;
        }
    }
}

if (!function_exists('stationRecordSolo')) {
    function stationRecordSolo($id)
    {
        $station = Station::whereId($id)->select([
            'id',
            'name',
            'system_id',
            'item_id',
            'corp_id',
            'alliance_id',
            'user_id',
            'text',
            'station_status_id',
            'out_time',
            'import_flag',
            'added_from_recon',
            'r_hash',
            'status_update',
            'timestamp',
            'show_on_rc_move',
            'show_on_rc',
            'added_by_user_id',
            'timer_image_link',
            'created_at',
            'updated_at',
            'standing'
        ])->with([
            'system',
            'corp',
            'alliance',
            'system', 'system.constellation',
            'system.region',
            'item',
            'status',
            'fc',
            'item',
            'recon',
            'gsoluser',
        ])->first();

        return $station;
    }
}

if (!function_exists('stationRecordAll')) {
    function stationRecordAll()
    {
        $station = Station::select([
            'id',
            'name',
            'system_id',
            'item_id',
            'corp_id',
            'alliance_id',
            'user_id',
            'text',
            'station_status_id',
            'out_time',
            'import_flag',
            'added_from_recon',
            'r_hash',
            'status_update',
            'timestamp',
            'show_on_rc_move',
            'show_on_rc',
            'added_by_user_id',
            'timer_image_link',
            'created_at',
            'updated_at',
            'standing'
        ])->with([
            'system',
            'corp',
            'alliance',
            'system', 'system.constellation',
            'system.region',
            'item',
            'status',
            'fc',
            'item',
            'recon',
            'gsoluser',
        ])->get();

        return $station;
    }
}



if (!function_exists('stationRecordByUserId')) {
    function stationRecordByUserId($id)
    {
        $station = Station::where('added_by_user_id', $id)->select([
            'id',
            'name',
            'system_id',
            'item_id',
            'corp_id',
            'alliance_id',
            'user_id',
            'text',
            'station_status_id',
            'out_time',
            'import_flag',
            'added_from_recon',
            'r_hash',
            'status_update',
            'timestamp',
            'show_on_rc_move',
            'show_on_rc',
            'added_by_user_id',
            'timer_image_link',
            'created_at',
            'updated_at',
            'standing'
        ])->with([
            'system',
            'corp',
            'alliance',
            'system', 'system.constellation',
            'system.region',
            'item',
            'status',
            'fc',
            'item',
            'recon',
            'gsoluser',
        ])->first();

        return $station;
    }
}

if (!function_exists('refreshToken')) {
    function refreshToken($charID)
    {
        $run = true;
        $char = Auth::where('char_id', $charID)->first();
        $refreshToken = $char->refresh_token;

        if ($char->expire_date <= now()) {
            $variables = json_decode(base64_decode(getenv('PLATFORM_VARIABLES')), true);
            $client_id = env('EVEONLINE_CLIENT_ID', ($variables && array_key_exists('EVEONLINE_CLIENT_ID', $variables)) ? $variables['EVEONLINE_CLIENT_ID'] : 'null');
            $client_secret = env('EVEONLINE_CLIENT_SECRET', ($variables && array_key_exists('EVEONLINE_CLIENT_SECRET', $variables)) ? $variables['EVEONLINE_CLIENT_SECRET'] : 'null');
            $code = base64_encode($client_id . ':' . $client_secret);
            $response = Http::withHeaders([
                'Authorization' => 'Basic ' . $code,
                'Content-Type' => 'application/x-www-form-urlencoded',
                'Host' => 'login.eveonline.com',
                'User-Agent' => 'webway evestuff@lol.com',
            ])->asForm()->post('https://login.eveonline.com/v2/oauth/token', [
                'grant_type' => 'refresh_token',
                'refresh_token' => $refreshToken,
            ]);

            if ($response->successful()) {
                $data = $response->collect();
                $newRefreshTime = now()->addMinutes(19);
                $char->update([
                    'expire_date' => $newRefreshTime,
                    'access_token' => $data['access_token'],
                    'refresh_token' => $data['refresh_token'],
                ]);

                return $run;
            } else {
                $run = false;

                return $run;
            }
        } else {
            return $run;
        }
    }
}

if (!function_exists('getNotifications')) {
    function getNotifications($charID)
    {
        $done = 0;
        $char = Auth::where('char_id', $charID)->first();
        $token = $char->access_token;

        do {
            $url = 'https://esi.evetech.net/latest/characters/' . $charID . '/notifications/';
            // $url = 'https://run.mocky.io/v3/8b7b063f-52fc-4f19-81cb-60eb8c37bc0f';


            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $token,
                'Content-Type' => 'application/x-www-form-urlencoded',
                'User-Agent' => 'evestuff evestuff@lol.com',
            ])->get($url);

            if ($response->successful()) {
                $data = Utils::jsonDecode($response->getBody(), true);
                $done = 3;
                $notifications = $data;

                return $notifications;
            } else {
                $errorCode = $response->status();
                switch ($errorCode) {
                    case 400:
                        $done++;
                        sleep(5);
                        break;

                    case 401:
                        $done++;
                        sleep(5);
                        break;

                    case 403:
                        $done++;
                        sleep(5);
                        break;

                    case 420:
                        $done++;
                        $headers = $response->headers();
                        $sleep = $headers['X-Esi-Error-Limit-Reset'][0];
                        sleep($sleep);
                        break;

                    case 500:
                        $done++;
                        sleep(5);
                        break;

                    case 503:
                        $done++;
                        sleep(5);
                        break;

                    case 504:
                        $done++;
                        sleep(5);
                        break;
                }
            }
        } while ($done != 3);
    }
}
