<?php

namespace App\Http\Controllers;

use App\Jobs\updateWebwayJob;
use App\Models\AdashLocalScan;
use App\Models\AdashLocalScanAlliance;
use App\Models\AdashLocalScanCorp;
use App\Models\Auth as ModelsAuth;
use App\Models\Campaign;
use App\Models\Categorie;
use App\Models\Corp;
use App\Models\DankOperation;
use App\Models\Dscan;
use App\Models\DscanItem;
use App\Models\DscanTotal;
use App\Models\Group;
use App\Models\Item;
use App\Models\NewCampaign;
use App\Models\NewCampaignOperation;
use App\Models\NewCampaignSystem;
use App\Models\NewOperation;
use App\Models\NewSystemNode;
use App\Models\NewUserNode;
use App\Models\OperationInfo;
use App\Models\OperationInfoDoctrine;
use App\Models\OperationInfoFleet;
use App\Models\OperationInfoUser;
use App\Models\OperationInfoUserList;
use App\Models\OperationUser;
use App\Models\OperationUserList;
use App\Models\Region;
use App\Models\Station;
use App\Models\System;
use App\Models\testNote;
use App\Models\TowerItem;
use App\Models\User;
use App\Models\WebWay;
use GuzzleHttp\Client;
use GuzzleHttp\Utils;
use Illuminate\Console\View\Components\Info;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Pusher\Pusher;
use Spatie\Activitylog\Models\Activity;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Traits\HasPermissions;
use Spatie\Permission\Traits\HasRoles;
use Termwind\Components\Dd;

class testController extends Controller
{
    use HasRoles;
    use HasPermissions;

    public function index()
    {
        return view('test2');
    }

    public function dscanTest(Request $request)
    {
        $check = Auth::user();
        if ($check->can('super')) {
            $dscan_results = $request->dscan;
            $newDscan = new Dscan();
            $newDscan->user_id = Auth::user()->id;
            $newDscan->link = Str::uuid();
            $newDscan->save();

            $rows = explode("\n", $dscan_results);
            $newTotal = new DscanTotal();
            $newTotal->dscan_id = $newDscan->id;
            $newTotal->save();

            foreach ($rows as $row) {
                $on = false;
                $columns = explode("\t", $row);
                $newDscanItem = new DscanItem();
                $newDscanItem->dscan_id = $newDscan->id;
                $newDscanItem->item_id = $columns[0];
                $newDscanItem->name = $columns[1];
                $components = explode(" ", $columns[3]);
                if (count($components) > 1) {
                    $newDscanItem->distance_value = $components[0]; // "9.1";
                    $newDscanItem->distance_unit = $components[1];
                } else {
                    $newDscanItem->distance_value = 0; // "9.1";
                    $newDscanItem->distance_unit = "m";
                }
                $newDscanItem->save();
                $item = Item::whereId($columns[0])->first();
                $group = Group::whereId($item->group_id)->first();
                $category = Categorie::whereId($group->category_id)->first();

                if ($newDscanItem->distance_unit == "km" && $newDscanItem->distance_value <= 8000) {
                    $on = true;
                }

                if ($newDscanItem->distance_unit == "m") {
                    $on = true;
                }

                $totals = $newTotal->totals;

                $current = $totals['items']['new'][$item->item_name]['gorup_id'] = $group->id;
                $current = $totals['items']['new'][$item->item_name]['category_id'] = $category->id;
                $current = $totals['items']['new'][$item->item_name]['item_name'] = $item->item_name;
                $current = $totals['items']['new'][$item->item_name]['item_id'] = $item->id;
                $newTotal->totals = $current;

                $current = $totals['groups']['new'][$group->name]['category_id'] = $category->id;
                $current = $totals['groups']['new'][$group->name]['group_id'] = $group->id;
                $current = $totals['groups']['new'][$group->name]['group_name'] = $group->name;
                $newTotal->totals = $current;

                $current = $totals['categories']['new'][$category->name]['category_id'] = $category->id;
                $current = $totals['categories']['new'][$category->name]['category_name'] = $category->name;
                $newTotal->totals = $current;

                if ($on) {
                    $current = $totals['items']['new'][$item->item_name]['on'] ?? 0;
                } else {
                    $current = $totals['items']['new'][$item->item_name]['off'] ?? 0;
                }

                $new = $current + 1;
                if ($on) {
                    $totals['items']['new'][$item->item_name]['on'] = $new;
                } else {

                    $totals['items']['new'][$item->item_name]['off'] = $new;
                }
                $newTotal->totals = $totals;
                $newTotal->save();

                $current = $totals['items']['new'][$item->item_name]['total'] ?? 0;
                $new = $current + 1;
                $totals['items']['new'][$item->item_name]['total'] = $new;
                $newTotal->save();

                if ($on) {
                    $current = $totals['categories']['new'][$category->name]['on'] ?? 0;
                } else {

                    $current = $totals['categories']['new'][$category->name]['off'] ?? 0;
                }

                $new = $current + 1;
                if ($on) {
                    $totals['categories']['new'][$category->name]['on'] = $new;
                } else {

                    $totals['categories']['new'][$category->name]['off'] = $new;
                }
                $newTotal->totals = $totals;
                $newTotal->save();

                $current = $totals['categories']['new'][$category->name]['total'] ?? 0;
                $new = $current + 1;
                $totals['categories']['new'][$category->name]['total'] = $new;
                $newTotal->save();

                if ($on) {
                    $current = $totals['groups']['new'][$group->name]['on'] ?? 0;
                } else {

                    $current = $totals['groups']['new'][$group->name]['off'] ?? 0;
                }

                $new = $current + 1;

                if ($on) {
                    $totals['groups']['new'][$group->name]['on'] = $new;
                } else {

                    $totals['groups']['new'][$group->name]['off'] = $new;
                }
                $newTotal->totals = $totals;
                $newTotal->save();

                $current = $totals['groups']['new'][$group->name]['total'] ?? 0;
                $new = $current + 1;
                $totals['groups']['new'][$group->name]['total'] = $new;
                $newTotal->totals = $totals;
                $newTotal->save();

                if (!$newDscan->system_id) {
                    $groupName = $item->group->name;
                    if ($groupName == "Sun") {
                        $systemName = explode(" - ", $columns[1])[0];
                        $system = System::where('system_name', $systemName)->first();
                        $newDscan->system_id = $system->id;
                        $newDscan->save();
                    }

                    if (
                        $group->id == 1406 ||
                        $group->id == 1876 ||
                        $group->id == 1657 ||
                        $group->id == 1404 ||
                        $group->id == 15
                    ) {
                        $systemName = explode(" - ", $columns[1])[0];
                        $system = System::where('system_name', $systemName)->first();
                        $newDscan->system_id = $system->id;
                        $newDscan->save();
                    }
                }
            }

            return [
                'link' => $newDscan->link,
            ];
        }
    }

    public function testDscanPull($id)
    {
        $check = Auth::user();
        if ($check->can('super')) {
            $dscan = Dscan::whereLink($id)
                ->with([
                    'system:id,region_id,constellation_id,system_name',
                    'system.region',
                    'system.constellation',
                    'user:id,name',
                    'items.item.group',
                ])
                ->first();
            return [
                'dscan' => $dscan,
            ];
        }
    }


    public function testTowerItems()
    {
        $check = Auth::user();
        if ($check->can('super')) {

            return TowerItem::with('item.group.category')->get();
        }
    }

    public function testDscanLocal(Request $request)
    {

        $check = Auth::user();
        if ($check->can('super')) {

            $dscan = Dscan::whereLink('8f939f04-bf9b-4ea1-bc0c-6c02d507a450')
                ->with([
                    'system:id,region_id,constellation_id,system_name',
                    'system.region',
                    'system.constellation',
                    'updatedBy:id,name',
                    'madeby:id,name',
                    'items.item.group',
                    'locals.corp.alliance'
                ])
                ->first();


            $allLocals = $dscan->locals;
            // return $allLocals;
            $corpsTotal = [];
            $allianceTotal = [];





            foreach ($allLocals as $local) {
                $corpsTotal[$local->corp->name]['details'] = $local->corp;
                $corpsTotal[$local->corp->name]['total'] = 0;
                $corpsTotal[$local->corp->name]['new'] = 0;
                $corpsTotal[$local->corp->name]['old'] = 0;

                $allianceTotal[$local->corp->alliance->name]['details'] = $local->corp->alliance;
                $allianceTotal[$local->corp->alliance->name]['total'] = 0;
                $allianceTotal[$local->corp->alliance->name]['new'] = 0;
                $allianceTotal[$local->corp->alliance->name]['old'] = 0;
            }

            foreach ($allLocals as $local) {
                $total =  $corpsTotal[$local->corp->name]['total'] ?? 0;
                $total++;
                $corpsTotal[$local->corp->name]['total'] = $total;

                $allianceTotal[$local->corp->alliance->name]['total'] = $allianceTotal[$local->corp->alliance->name]['total'] + 1;



                if ($local->pivot->new) {
                    $new =  $corpsTotal[$local->corp->name]['new'] ?? 0;
                    $new++;
                    $corpsTotal[$local->corp->name]['new'] = $new;

                    $allianceTotal[$local->corp->alliance->name]['new'] = $allianceTotal[$local->corp->alliance->name]['new'] + 1;
                }

                if ($local->pivot->openssl_decrypt) {
                    $old =  $corpsTotal[$local->corp->name]['old'] ?? 0;
                    $old++;
                    $corpsTotal[$local->corp->name]['old'] = $old;

                    $allianceTotal[$local->corp->alliance->name]['old'] = $allianceTotal[$local->corp->alliance->name]['old'] + 1;
                }
            }

            foreach ($corpsTotal as $key => $corp) {
                $old =   $corpsTotal[$key]['old'];
                $new =   $corpsTotal[$key]['new'];
                $diff = $new - $old;
                $corpsTotal[$key]['diff'] = $diff;
            }

            foreach ($allianceTotal as $key => $alliance) {
                $old =   $allianceTotal[$key]['old'];
                $new =   $allianceTotal[$key]['new'];
                $diff = $new - $old;
                $allianceTotal[$key]['diff'] = $diff;
            }

            return $allianceTotal;
            return [
                'dscan' => $dscan,
                'corpsTotal' => $corpsTotal,
                'allianceTotal' => $allianceTotal,
            ];
        }
    }

    public function testItemPull()
    {
        $check = Auth::user();
        if ($check->can('super')) {
            $items = Item::whereNull('item_name')->get();
            foreach ($items as $item) {
                $pull = 0;
                $itemID = $item->id;

                $item = Item::where('id', $itemID)->first();

                do {

                    $response = Http::withHeaders([
                        'Content-Type' => 'application/json',
                        'Accept' => 'application/json',
                        'User-Agent' => 'evestuff.online evestuff@lol.com',
                    ])->get('https://esi.evetech.net/latest/universe/types/' . $itemID . '/?datasource=tranquility&language=en');
                    if ($response->successful()) {
                        $itemres = $response->json();
                        dd($itemres);
                        $item->name = $itemres['name'];
                    } else {
                        $headers = $response->headers();
                        $sleep = $headers['X-Esi-Error-Limit-Reset'][0];
                        sleep($sleep);
                        $pull++;
                    }
                } while ($pull != 3);
            }
        }
    }

    public function testDscanHistory($link)
    {
        $check = Auth::user();
        if ($check->can('super')) {
            return  loadDscanHistory($link);
        }
    }

    public function testWatchListPull()
    {
        $check = Auth::user();
        if ($check->can('super')) {

            $groups = Group::all();
            foreach ($groups as $group) {

                $response = Http::withHeaders([
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                    'User-Agent' => 'evestuff.online evestuff@lol.com',
                ])->get('https://esi.evetech.net/latest/universe/groups/' . $group->id . '/?datasource=tranquility&language=en');

                $res = $response->json();
                dd($res);
                $name = $res['name'];
                $group->name = $name;
                $group->save();
                $types = $res['types'];
                foreach ($types as $type) {
                    $item = Item::where('id', $type)->first();
                    $item->group_id = $group->id;
                    $item->save();
                }
            }
        }
    }

    public function testStationPull($id)
    {
        $check = Auth::user();
        if ($check->can('super')) {
            return reconRegionPullIdCheck($id);
        }
    }

    public function testStationItemPull($id)
    {
        $check = Auth::user($id);
        if ($check->can('super')) {
            $text = getStationFitBlockSolo($id);
            Station::where('id', $id)->update(['fit_text' => $text]);
            $station = Station::where('id', $id)->first();
            dd($text, $station->fit_text);
        }
    }

    public function removeFC()
    {
        $check = Auth::user();
        if ($check->can('super')) {
            $users = User::all();
            foreach ($users as $user) {
                $user->removeRole(12);
            }
        }
    }

    public function testStation()
    {
        return towerRecordAll();
    }

    public function removeOps()
    {
        $check = Auth::user();
        if ($check->can('super')) {
            $role = Role::where('name', 'Ops')->first();
            $permissions = Permission::get();

            foreach ($permissions as $permission) {
                $permission->removeRole($role);
            }
        }
    }

    public function key()
    {
        $opID = 1;
        // $users = OperationUserList::where('operation_id', $opID)
        //     ->with(['opUsers' => function ($query) use ($opID) {
        //         $query->where('operation_id', $opID);
        //     }])->withCount(['opUsers' => function ($query) use ($opID) {
        //     $query->where('operation_id', $opID);
        // }])->get();

        $users = OperationUserList::where('operation_id', $opID)
            ->withCount(['ownUsers' => function ($query) use ($opID) {
                $query->where('operation_id', $opID);
            }])->get();
        // return $users;
        foreach ($users as $user) {
            return $user->own_users_count;
        }

        return $users;
    }

    public function testUpdateScore()
    {
        $user = Auth::user();
        if ($user->can('super')) {
            $opID = 9;
            $campaignID = 97625;
            $message = NewOperation::where('id', $opID)
                ->with([
                    'campaign' => function ($q) use ($campaignID) {
                        // dd($q);
                        $q->where('campaign_id', $campaignID);
                    },
                    'campaign.status',
                    'campaign.constellation:id,constellation_name,region_id',
                    'campaign.constellation.region:id,region_name',
                    'campaign.alliance:id,name,ticker,standing,url,color',
                    'campaign.system:id,system_name,adm',
                ])
                ->first();

            return $message;
        }
    }

    public function testDankFleet()
    {
        $user = Auth::user();
        if ($user->can('super')) {

            $paste = "https://fleets.apps.gnf.lt/fleet/operation/6f999056-b0ee-4090-92d7-a9324e450078";
            $opID = basename($paste);
            $url1 = "https://fleets.apps.gnf.lt/api/v1/coordination/operation/" . $opID . "/fleets";
            $url = "https://fleets.apps.gnf.lt/api/v1/coordination/operation/" . $opID;

            $variables = json_decode(base64_decode(getenv('PLATFORM_VARIABLES')), true);
            $token = env('DANK_TOKEN', ($variables && array_key_exists('DANK_TOKEN', $variables)) ? $variables['DANK_TOKEN'] : 'null');
            $response = Http::withHeaders([
                'X-ApiKey' => $token,
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'User-Agent' => 'evestuff.online evestuff@lol.com',
            ])->get($url1);

            $fleets = $response->json();

            $response = Http::withHeaders([
                'X-ApiKey' => $token,
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'User-Agent' => 'evestuff.online evestuff@lol.com',
            ])->get($url);

            $operation = $response->json();
            dd($operation);

            if (DankOperation::where('id', $opID)->count() == 0) {
                $new = new DankOperation();
                $new->id = $operation['id'];
                $new->name = $operation['name'];
                $new->dank_id = $opID;
                $new->closed_at = $operation['closedAt'] ?? null;
                $new->operation_info_id = 1;
                $new->save();
            } else {
                $old = DankOperation::where('id', $opID)->first();
                $old->closed_at = $operation['closedAt'] ?? null;
                $old->name = $operation['name'];
                $old->save();
            }

            foreach ($fleets as $fleet) {
                $old = OperationInfoFleet::where('uid', $fleet['id'])->first();
                $docID = OperationInfoDoctrine::where('name', $fleet['setupName'])->first();
                if ($old) {
                    $old->uid = $fleet['id'];
                    $old->name = $fleet['name'];
                    $old->started = $fleet['startedAt'];
                    $old->closed = $fleet['closedAt'];
                    $old->fc_id = $fleet['fleetCommanderId'];
                    $old->boss_id = $fleet['fleetBossId'];
                    $old->dank_operation_id = $operation['id'];
                    $old->doctrine_id = $docID->id ?? null;
                    $old->save();
                } else {

                    $checkCustomFleet = OperationInfoFleet::where('uid', '!=', $fleet['id'])
                        ->where('fc_id', $fleet['fleetCommanderId'])
                        ->first();

                    if ($checkCustomFleet) {
                        $checkCustomFleet->uid = $fleet['id'];
                        $checkCustomFleet->name = $fleet['name'];
                        $checkCustomFleet->started = $fleet['startedAt'];
                        $checkCustomFleet->closed = $fleet['closedAt'];
                        $checkCustomFleet->fc_id = $fleet['fleetCommanderId'];
                        $checkCustomFleet->boss_id = $fleet['fleetBossId'];
                        $checkCustomFleet->dank_operation_id = $operation['id'];
                        $checkCustomFleet->doctrine_id = $docID->id ?? null;
                        $checkCustomFleet->save();
                    } else {
                        $new = new OperationInfoFleet();
                        $new->uid = $fleet['id'];
                        $new->name = $fleet['name'];
                        $new->started = $fleet['startedAt'];
                        $new->closed = $fleet['closedAt'];
                        $new->fc_id = $fleet['fleetCommanderId'];
                        $new->boss_id = $fleet['fleetBossId'];
                        $new->dank_operation_id = $operation['id'];
                        $new->doctrine_id = $docID->id ?? null;
                        $new->save();
                    }
                }

                $bossCheck = OperationInfoUser::where('id', $fleet['fleetBossId'])->first();
                if (!$bossCheck) {
                    $url = "https://images.evetech.net/characters/" . $fleet['fleetBossId'] . "/portrait?tenant=tranquility&size=64";
                    $new = new OperationInfoUser();
                    $new->id = $fleet['fleetBossId'];
                    $new->name = $fleet['fleetBoss'];
                    $new->url = $url;
                    $new->corporation_id = $fleet['fleetBossCorporationId'];
                    $new->alliance_id = $fleet['fleetBossAllianceId'];
                    $new->save();
                }

                $fcCheck = OperationInfoUser::where('id', $fleet['fleetCommanderId'])->first();
                if (!$fcCheck) {
                    $url = "https://images.evetech.net/characters/" . $fleet['fleetCommanderId'] . "/portrait?tenant=tranquility&size=64";
                    $new = new OperationInfoUser();
                    $new->id = $fleet['fleetCommanderId'];
                    $new->name = $fleet['fleetCommander'];
                    $new->url = $url;
                    $new->corporation_id = $fleet['fleetCommanderCorporationId'];
                    $new->alliance_id = $fleet['fleetCommanderAllianceId'];
                    $new->save();
                }
            }
        }
    }

    public function testnamejob($name)
    {
        return nameToID($name);
    }

    public function adashLScan()
    {
        $user = Auth::user();
        if ($user->can('super')) {

            $systemID = 30004759;
            // $variables = json_decode(base64_decode(getenv('PLATFORM_VARIABLES')), true);
            // $token = env('ADASH_TOKEN', ($variables && array_key_exists('ADASH_TOKEN', $variables)) ? $variables['ADASH_TOKEN'] : 'null');
            // $response = Http::withHeaders([
            //     'aD_APISecret' => $token,
            //     'Content-Type' => 'application/json',
            //     'Accept' => 'application/json',
            //     'User-Agent' => 'evestuff.online evestuff@lol.com',
            // ])->get('https://adashboard.info/recon/api/lo/recentfromsystem/' . $systemID);

            // $data =  $response->json();
            // return $data;
            $json = '[{"0NolYGOT":[{"total":177},{"alliances":[{"1354830081":"CONDI(125)"},{"99004425":"BASTN(13)"},{"99009163":"D.C(12)"},{"150097440":"LAWN(7)"},{"1900696668":"INIT.(5)"}]},{"unaffiliated":[{"98641388":"SHOWU(7)"}]}]},{"2w9OuYpC":[{"total":175},{"alliances":[{"1354830081":"CONDI(126)"},{"99004425":"BASTN(13)"},{"99009163":"D.C(10)"},{"150097440":"LAWN(7)"}]},{"unaffiliated":[{"98641388":"SHOWU(7)"}]}]}]';
            $data = json_decode($json, true);
            if (count($data) > 0) {
                foreach ($data as $scan) {
                    $hash = key($scan);

                    foreach ($scan[$hash] as $key => $loop) {
                        if (key($loop) == "total") {
                            $total = $loop['total'];
                        }

                        if (key($loop) == "alliances") {
                            $alliances = $loop['alliances'];
                        }

                        if (key($loop) == "unaffiliated") {
                            $unaffiliated = $loop['unaffiliated'];
                        }
                    }
                    $a = collect();
                    foreach ($alliances as $aKey => $alliance) {
                        $allianceID = key($alliance);
                        $text = $alliance[$allianceID];
                        $text = substr($text, 0, -1);
                        $textx = explode("(", $text);
                        $textx[1];

                        $b = collect(['id' => $allianceID, 'count' => intval($textx[1])]);
                        $a->push($b);
                    }
                    $c = collect();
                    foreach ($unaffiliated as $uKey => $un) {
                        $corpID = key($un);
                        $text = $un[$corpID];
                        $text = substr($text, 0, -1);
                        $textx = explode("(", $text);
                        $textx[1];

                        $d = collect(['id' => $corpID, 'count' => intval($textx[1])]);
                        $c->push($d);
                    }

                    $check = AdashLocalScan::where('hash', $hash)->count();
                    if ($check == 0) {
                        $new = new AdashLocalScan();
                        $new->system_id = $systemID;
                        $new->hash = $hash;
                        $new->total = $total;
                        $new->unaffiliated = 0;
                        $new->save();
                        $scanID = $new->id;

                        foreach ($a as $al) {
                            $newA = new AdashLocalScanAlliance();
                            $newA->adash_local_scan_id = $scanID;
                            $newA->alliance_id = $al['id'];
                            $newA->count = $al['count'];
                            $newA->save();
                        }

                        foreach ($c as $co) {
                            $newC = new AdashLocalScanCorp();
                            $newC->adash_local_scan_id = $scanID;
                            $newC->corp_id = $co['id'];
                            $newC->count = $co['count'];
                            $newC->save();
                        }
                    }

                    // dd($data[0][$hash], $hash, $total, $a, $unaffiliated);
                }

                $count = AdashLocalScan::where('system_id', $systemID)->count();
                if ($count > 5) {
                    $take = $count - 5;
                    $olds = AdashLocalScan::latest()->take($take)->get();
                    foreach ($olds as $old) {
                        AdashLocalScanAlliance::where('adash_local_scan_id', $old->id)->delete();
                        AdashLocalScanCorp::where('adash_local_scan_id', $old->id)->delete();
                        $old->delete();
                    }
                }
            }
        }
    }

    public function adashDScan()
    {
        $user = Auth::user();
        if ($user->can('super')) {

            $systemID = 30004759;
            $variables = json_decode(base64_decode(getenv('PLATFORM_VARIABLES')), true);
            $token = env('ADASH_TOKEN', ($variables && array_key_exists('ADASH_TOKEN', $variables)) ? $variables['ADASH_TOKEN'] : 'null');
            $response = Http::withHeaders([
                'aD_APISecret' => $token,
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'User-Agent' => 'evestuff.online evestuff@lol.com',
            ])->get('https://adashboard.info/recon/api/ds/recentfromsystem/' . $systemID);

            $data = $response->json();
            return $data;
            // $json = '[{"hbVcQHOh":[{"total":128},{"alliances":[{"1354830081":"CONDI(96)"},{"99004425":"BASTN(11)"}]},{"unaffiliated":[]}]},{"0Tz5k6yS":[{"total":128},{"alliances":[{"1354830081":"CONDI(96)"},{"99004425":"BASTN(11)"}]},{"unaffiliated":[]}]},{"MH3Iixzr":[{"total":128},{"alliances":[{"1354830081":"CONDI(96)"},{"99004425":"BASTN(11)"}]},{"unaffiliated":[]}]}]';
            // $data = json_decode($json, true);
            if (count($data) > 0) {
                foreach ($data as $scan) {
                    $hash = key($scan);

                    foreach ($scan[$hash] as $key => $loop) {
                        if (key($loop) == "total") {
                            $total = $loop['total'];
                        }

                        if (key($loop) == "alliances") {
                            $alliances = $loop['alliances'];
                        }

                        if (key($loop) == "unaffiliated") {
                            $unaffiliated = $loop['unaffiliated'];
                        }
                    }
                    $a = collect();
                    foreach ($alliances as $aKey => $alliance) {
                        $allianceID = key($alliance);
                        $text = $alliance[$allianceID];
                        $text = substr($text, 0, -1);
                        $textx = explode("(", $text);
                        $textx[1];

                        $b = collect(['id' => $allianceID, 'count' => intval($textx[1])]);
                        $a->push($b);
                    }

                    $check = AdashLocalScan::where('hash', $hash)->count();
                    if ($check == 0) {
                        $new = new AdashLocalScan();
                        $new->system_id = $systemID;
                        $new->hash = $hash;
                        $new->total = $total;
                        $new->unaffiliated = 0;
                        $new->save();
                        $scanID = $new->id;

                        foreach ($a as $al) {
                            $newA = new AdashLocalScanAlliance();
                            $newA->adash_local_scan_id = $scanID;
                            $newA->alliance_id = $al['id'];
                            $newA->count = $al['count'];
                            $newA->save();
                        }
                    }

                    // dd($data[0][$hash], $hash, $total, $a, $unaffiliated);
                }

                $count = AdashLocalScan::where('system_id', $systemID)->count();
                if ($count > 5) {
                    $take = $count - 5;
                    $olds = AdashLocalScan::latest()->take($take)->get();
                    foreach ($olds as $old) {
                        AdashLocalScanAlliance::where('adash_local_scan_id', $old->id)->delete();
                        $old->delete();
                    }
                }
            }
        }
    }

    public function dankDoc()
    {
        $user = Auth::user();
        if ($user->can('super')) {

            $variables = json_decode(base64_decode(getenv('PLATFORM_VARIABLES')), true);
            $token = env('DANK_TOKEN', ($variables && array_key_exists('DANK_TOKEN', $variables)) ? $variables['DANK_TOKEN'] : 'null');
            $response = Http::withHeaders([
                'X-ApiKey' => $token,
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'User-Agent' => 'evestuff.online evestuff@lol.com',
            ])->get('https://fleets.apps.gnf.lt/api/v1/coordination/fleet-setups');

            $datas = $response->json();
            foreach ($datas as $data) {
                dd($data['id'], $data['name']);
            }
        }
    }

    public function testClearCampaigns()
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

    public function nameToID($name)
    {
        $user = Auth::user();
        if ($user->can('super')) {
            $check = OperationInfoUser::where('name', $name)->count();
            if ($check == 0) {
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

                foreach ($userIds as $userID) {
                    $response = Http::withHeaders([
                        'Content-Type' => 'application/json',
                        'Accept' => 'application/json',
                        'User-Agent' => 'evestuff.online evestuff@lol.com',
                    ])->get('https://esi.evetech.net/latest/characters/' . $userID . '/?datasource=tranquility');
                    $res = $response->collect($key = null);
                    $new = new OperationInfoUser();
                    $new->id = $userID;
                    $new->alliance_id = $res['alliance_id'] ?? null;
                    $new->corporation_id = $res['corporation_id'];
                    $new->name = $res['name'];
                    $new->url = 'https://images.evetech.net/characters/' . $userID . '/portrait?tenant=tranquility&size=64';
                    $new->save();
                }
            } else {
                return 'ALREADY THERE';
            }
        }
    }

    public function testEveStatus()
    {
        $user = Auth::user();
        if ($user->can('super')) {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'User-Agent' => 'evestuff.online evestuff@lol.com',
            ])->get('https://esi.evetech.net/status.json?version=latest');
            $status = $response->collect();

            foreach ($status as $status) {
                $endpoint = $status['endpoint'];
                $method = $status['method'];
                $stat = $status['status'];
                $tag = $status['tags'][0];

                echo $endpoint;
                echo $method;
                echo $stat;
                echo $tag;
                echo '<pre>';
                echo 'NEW';
                echo '</pre>';
            }
        } else {
            return null;
        }
    }

    public function testPusher()
    {
        $user = Auth::user();
        $flag = null;
        if ($user->can('super')) {
            OperationInfoUserList::whereNotNull('id')->update(['delete' => 1]);
            $variables = json_decode(base64_decode(getenv('PLATFORM_VARIABLES')), true);
            $pusher = new Pusher(
                env('PUSHER_APP_KEY', ($variables && array_key_exists('PUSHER_APP_KEY', $variables)) ? $variables['PUSHER_APP_KEY'] : 'null'),
                env('PUSHER_APP_SECRET', ($variables && array_key_exists('PUSHER_APP_SECRET', $variables)) ? $variables['PUSHER_APP_SECRET'] : 'null'),
                env('PUSHER_APP_ID', ($variables && array_key_exists('PUSHER_APP_ID', $variables)) ? $variables['PUSHER_APP_ID'] : 'null'),
                [
                    'cluster' => env('PUSHER_APP_CLUSTER', ($variables && array_key_exists('PUSHER_APP_CLUSTER', $variables)) ? $variables['PUSHER_APP_CLUSTER'] : 'null'),
                    'encrypted' => true,
                    'useTLS' => true,
                    'host' => 'https://sockets.scopeh.co.uk',
                    'port' => 443,
                    'scheme' => 'https',
                ]
            );

            $response = $pusher->get('/channels');
            $response = json_decode(json_encode($response), true);
            $channels = $response['channels'];
            $channels = array_keys($channels);
            $data = collect([]);

            foreach ($channels as $channel) {
                $part = explode('.', $channel);
                if ($part[0] === 'private-operationinfooppageown') {
                    $keys = collect(['userID', 'opID']);
                    $info = explode('-', $part[1]);
                    $data1 = collect($info);
                    $data1 = $keys->combine($data1);
                    $data->push($data1);
                }
            }
            $groups = $data->groupBy('opID');
            // return $groups;
            foreach ($groups as $group) {
                $opID = (int) $group[0]['opID'];
                foreach ($group as $op) {
                    $userID = (int) $op['userID'];
                    $check = OperationInfoUserList::where('operation_info_id', $opID)->where('user_id', $userID)->first();
                    if (!$check) {
                        $newOp = new OperationInfoUserList();
                        $newOp->operation_info_id = $opID;
                        $newOp->user_id = $userID;
                        $newOp->delete = 2;
                        $newOp->save();
                    } else {
                        $check->delete = 0;
                        $check->save();
                    }
                }
            }

            $deleteCheck = OperationInfoUserList::where('delete', 1)->groupBy('operation_info_id')->pluck('operation_info_id');
            $addCheck = OperationInfoUserList::where('delete', 2)->groupBy('operation_info_id')->pluck('operation_info_id');
            $combined = $deleteCheck->merge($addCheck);
            $combined = $combined->unique();
            dd($combined);
            OperationInfoUserList::where('delete', 1)->delete();
            OperationInfoUserList::where('delete', 2)->update(['delete' => 0]);
            foreach ($combined as $op) {
                $this->info($op);
                broadcastOperationInfoUserList($op, 18);
            }
        }
    }

    public function testRunScore()
    {
        $user = Auth::user();
        if ($user->can('super')) {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'User-Agent' => 'evestuff.online evestuff@lol.com',
            ])->get('https://run.mocky.io/v3/8b7b063f-52fc-4f19-81cb-60eb8c37bc0f');
            return $response;

            foreach ($campaigns as $campaign) {
                $event_type = $campaign['event_type'];
                if ($event_type == 'ihub_defense' || $event_type == 'tcu_defense') {
                    $score_changed = false;
                    if ($event_type == 'ihub_defense') {
                        $event_type = 32458;
                    } else {
                        $event_type = 32226;
                    }

                    $id = $campaign['campaign_id'];
                    $old = NewCampaign::where('id', $id)->first();
                    if ($old) {
                        // * Checking if the score has changed
                        if ($campaign['attackers_score'] != $old->attackers_score) {
                            $attackers_score_old = $old->attackers_score;
                            $defenders_score_old = $old->defenders_score;
                            $old->update([
                                'attackers_score_old' => $attackers_score_old,
                                'defenders_score_old' => $defenders_score_old,
                            ]);
                            $score_changed = true;
                        }
                    }

                    $time = $campaign['start_time'];
                    $start_time = fixtime($time);
                    $data = [];
                    $data = [
                        'attackers_score' => $campaign['attackers_score'],
                        'constellation_id' => $campaign['constellation_id'],
                        'alliance_id' => $campaign['defender_id'],
                        'defenders_score' => $campaign['defender_score'],
                        'event_type' => $event_type,
                        'system_id' => $campaign['solar_system_id'],
                        'start_time' => $start_time,
                        'structure_id' => $campaign['structure_id'],
                        'check' => 1,
                    ];

                    NewCampaign::updateOrCreate(['id' => $id], $data);
                    echo $score_changed;
                    // * If Score has changed

                    if ($score_changed) {
                        echo ' -  I AM IN   -';
                        $campaign = NewCampaign::where('id', $id)->first();
                        $campaignOperations = NewCampaignOperation::where('campaign_id', $id)->get();
                        $bNode = $campaign->b_node;
                        $rNode = $campaign->r_node;
                        echo $id;
                        $campaignNodes = NewSystemNode::where('campaign_id', $id)->whereIn('node_status', [4, 5])->get();
                        foreach ($campaignNodes as $campaignNode) {
                            $system_id = $campaignNode->system_id;
                            if ($campaignNode->node_status == 4) {
                                $bNode = $bNode + 1;
                                echo 'yay add 1 to blue';
                            } else {
                                $rNode = $rNode + 1;
                                echo 'yay add 1 to red';
                            }
                            $campaignNode->delete();
                            broadcastsystemSolo($system_id, 7);
                            operationInfoSoloSystemBCast($system_id, 16);
                        }

                        $campaign->update(['b_node' => $bNode, 'r_node' => $rNode]);
                        foreach ($campaignOperations as $campaignOperation) {
                            broadcastCampaignSolo($campaign->id, $campaignOperation->operation_id, 4);
                        }
                    }

                    // * Setting everything up for a new campaign
                    if (NewCampaignOperation::where('campaign_id', $id)->count() == 0) {
                        $uuid = Str::uuid();
                        $system = System::where('id', $campaign['solar_system_id'])->first();
                        $systemName = $system->system_name;
                        if ($event_type == 32458) {
                            $type = 'Ihub';
                        } else {
                            $type = 'TCU';
                        }
                        $title = $systemName . ' - ' . $type;
                        $newOp = NewOperation::create([
                            'link' => $uuid,
                            'solo' => 1,
                            'status' => 1,
                            'title' => $title,
                        ]);

                        NewCampaignOperation::create([
                            'campaign_id' => $id,
                            'operation_id' => $newOp->id,
                        ]);

                        $campaignSystemsIDs = System::where('constellation_id', $campaign['constellation_id'])->pluck('id');
                        foreach ($campaignSystemsIDs as $systemid) {
                            NewCampaignSystem::create([
                                'system_id' => $systemid,
                                'new_campaign_id' => $id,
                            ]);
                        }
                    }
                }
            }

            $noCampaigns = NewOperation::where('status', '!=', 0)->doesntHave('campaign')->get();
            foreach ($noCampaigns as $noCampaign) {
                $n = NewCampaignOperation::where('operation_id', $noCampaign->id)->get();
                foreach ($n as $n) {
                    $n->delete();
                }
                $noCampaign->delete();
            }

            // * Change new upcoming status to warmup (done an hour before start time)
            $warmupCampaigns = NewCampaign::where('start_time', '>', now())
                ->where('start_time', '<=', now()->addHour())
                ->where('status_id', 1)
                ->where('check', 1)
                ->get();
            foreach ($warmupCampaigns as $start) {
                $start->update(['status_id' => 5, 'check' => 1]);
                $opIDs = NewCampaignOperation::where('campaign_id', $start->id)->get();
                foreach ($opIDs as $opID) {
                    broadcastCampaignSolo($start->id, $opID->operation_id, 4);
                }
            }

            // * Checks to see if a campaign has moved from warmup to active
            $startedCampaigns = NewCampaign::where('start_time', '<=', now())
                ->where('status_id', 5)
                ->where('check', 1)
                ->get();
            foreach ($startedCampaigns as $start) {
                $start->update(['status_id' => 2, 'check' => 1]);
                $opIDs = NewCampaignOperation::where('campaign_id', $start->id)->get();
                foreach ($opIDs as $opID) {
                    broadcastCampaignSolo($start->id, $opID->operation_id, 4);
                }
            }

            //! IF CHECK = 0, that means its not on the API which means the campaing is over.
            // * Set Campaign to finished(3) but able to access still for 10mins
            $n = NewCampaign::where('check', 0)
                ->whereNull('end_time')->get();

            foreach ($n as $n) {
                $n->update([
                    'end_time' => now(),
                    'status_id' => 3,
                    'check' => 1,
                ]);
            }

            // * Check if the campaign have been over more than 10mins, if true set it to finsiehd(3)
            $n = NewCampaign::where('check', 0)
                ->where('status_id', 2)
                ->where('end_time', '>', now()->subMinutes(10))->get();

            foreach ($n as $n) {
                $n->update([
                    'status_id' => 3,
                    'check' => 1,
                ]);
            }

            // * If campaign have been over for more than 10mins set it to finished(4), to show on the finished tab for 24 hours
            $n = NewCampaign::where('check', 0)
                ->where('status_id', 3)
                ->where('end_time', '<', now()->subMinutes(10))->get();
            foreach ($n as $n) {
                $n->update([
                    'status_id' => 4,
                    'check' => 1,
                ]);
            }

            // * If campaign has been over for more than 24 hours.  Delete the campaign.
            $n = NewCampaign::where('check', 0)
                ->where('status_id', 4)
                ->where('end_time', '<', now()->subDay())->get();
            foreach ($n as $n) {
                $n->update([
                    'status_id' => 10,
                    'check' => 1,
                ]);
            }
        }
    }

    function testAthing()
    {
        $user = User::where('name', 'Webway')->first();
        if ($user->fc_notes) {
            $user->fc_notes = null;
        } else {
            $user->fc_notes = "write away";
        }
        $user->save();
    }

    public function testOpLogging()
    {
        $user = Auth::user();
        if ($user->can('super')) {
            $op = OperationInfo::first();
            activity()->on($op)->event('joined')->useLog('Operation Info')->log('joined');
            $lastActivity = Activity::where('subject_id', 15)->where('log_name', 'Operation Info')->with(['subject', 'causer'])->latest()->first();
            return $lastActivity;
        }
    }

    public function corptest()
    {
        $w = WebWay::where('id', '>', 0)->get();
        foreach ($w as $w) {
            $w->update(['active' => 0]);
        }
        $stations = Station::get();
        $stationSystems = $stations->pluck('system_id');
        $campaigns = Campaign::get();
        $campaginSystems = $campaigns->pluck('system_id');

        $systemIDs = $stationSystems->merge($campaginSystems);
        $systemIDs = $systemIDs->unique();
        $systemIDs = $systemIDs->values();
        $w = WebWay::whereIn('system_id', $systemIDs)->get();
        foreach ($w as $w) {
            $w->update(['active' => 1]);
        }
        $w = WebWay::where('active', 0)->get();
        foreach ($w as $w) {
            $w->delete();
        }

        foreach ($systemIDs as $system_id) {
            updateWebwayJob::dispatch($system_id)->onQueue('slow');
        }
    }

    public function popualteCampaignSystemTable()
    {
    }

    public static function getCorpWithNoAlliance()
    {
        $corpID = null;
        $corpTciker = null;
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'User-Agent' => 'evestuff.online evestuff@lol.com',
        ])->post('https://esi.evetech.net/latest/universe/ids/?datasource=tranquility&language=en', ['monty']);

        $returns = $response->collect();
        dd($returns);
        foreach ($returns as $key => $var) {
            if ($key == 'corporations') {
                $corpRep = Http::withHeaders([
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                    'User-Agent' => 'evestuff.online evestuff@lol.com',
                ])->get('https://esi.evetech.net/latest/corporations/' . $var[0]['id'] . '/?datasource=tranquility');

                $corpReturn = $corpRep->collect();
                // Corp::create([
                //     'id' => $var[0]['id'],
                //     "name" => $corpReturn["name"],
                //     'ticker' => $corpReturn["ticker"],
                //     'url' => "https://images.evetech.net/Corporation/" . $var[0]['id'] . "_64.png",
                //     'active' => 1
                // ]);

                // $corpID = $var[0]['id'];
                // $corpTciker = $corpReturn["ticker"];
            }
        }

        // $tickerlist = Corp::select(['ticker as text', 'id as value'])->get();

        // return [
        //     'ticklist' => $tickerlist,
        //     'corpID' => $corpID,
        //     'corpTicker' => $corpTciker
        // ];
    }

    public function prequal()
    {
        $user = Auth::user();
        if ($user->can('super')) {
            return redirect('/a524f35da058742f0defd6fb0db6afc4');
        } else {
            return null;
        }
    }

    public function getSoloOperations()
    {
        $user = Auth::user();
        if ($user->can('super')) {
            return ['operations' => NewOperation::where('solo', 1)
                ->with([
                    'campaign',
                    'campaign.constellation:id,constellation_name',
                    'campaign.alliance:id,name,ticker,standing,url,color',
                    'campaign.system:id,system_name,adm',
                    'campaign.system.webway' => function ($t) {
                        $t->where('permissions', 1);
                    },
                    'campaign.structure:id,item_id,age',
                ])
                ->get()];
        } else {
            return null;
        }
    }

    public function horizon()
    {
        $user = Auth::user();
        if ($user->can('super')) {
            return redirect('/a3bc619080ec6c04c44fffff8cc780de');
        } else {
            return null;
        }
    }

    public function logreader()
    {
        $user = Auth::user();
        if ($user->can('super')) {
            return redirect('/c26c3ba256e4564ca5a8215dc8e13fe9');
        } else {
            return null;
        }
    }

    public function testGetAlliance($id)
    {
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'User-Agent' => 'evestuff.online evestuff@lol.com',
        ])->get('https://esi.evetech.net/latest/alliances/' . $id . '/?datasource=tranquility');
        $allianceInfo = $response->collect();

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'User-Agent' => 'evestuff.online evestuff@lol.com',
        ])->get('https://esi.evetech.net/latest/alliances/' . $id . '/corporations/?datasource=tranquility');
        $corpIDs = $response->collect();

        dd($allianceInfo, $corpIDs);
    }

    public function testStationRecords($type)
    {
        $data = StationRecords($type);

        return ['stations' => $data];
    }

    public function testPull()
    {
        $client = new Client();
        $headers = [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'User-Agent' => 'evestuff.online evestuff@lol.com',
        ];
        $url = 'https://esi.evetech.net/latest/sovereignty/campaigns/?datasource=tranquility';
        $response = $client->request('GET', $url, [
            'headers' => $headers,
        ]);
        $response = Utils::jsonDecode($response->getBody(), true);
        dd($response);
    }

    public function campaginTest()
    {
        $regionIDs = collect();
        $regions = NewOperation::where('solo', 1)->with('campaign.constellation.region')->get();
        foreach ($regions as $r) {
            $regionIDs->push($r->campaign[0]->constellation->region->id);
        }

        $uRegionIDs = $regionIDs->unique();
        $regionList = Region::whereIn('id', $uRegionIDs)->select(['id as value', 'region_name as text'])->orderBy('region_name', 'asc')->get();

        return ['regionList' => $regionList];
    }

    public function campaginListTest()
    {
        if (Auth::user()->can('super')) {
            $list = NewOperation::where('solo', 1)
                ->with([
                    'campaign.constellation:id,constellation_name',
                    'campaign.alliance:id,name,ticker,standing,url,color',
                    'campaign.system:id,system_name,adm',
                    'campaign.structure:id,age',
                ])
                ->get();

            return ['list' => $list];
        }
    }

    public function corptest2()
    {
        $variables = json_decode(base64_decode(getenv('PLATFORM_VARIABLES')), true);
        /*
        send = [
        startSystem => start system get from env (1dq)
        endStstem => $system_id
        ]
        return =  api code to request too webway repos $response will be:
        [
        link: UUID of the saved route
        jumps: number of jumps from 1dq ( ID got from env file) to target system
        link_p: UUID of the saved route (with permissions)
        jumps_p: number of jumps from 1dq ( ID got from env file) to target system (with permissions)
        ]
         */

        $startSystem = env('HOME_SYSTEM_ID', ($variables && array_key_exists('HOME_SYSTEM_ID', $variables)) ? $variables['HOME_SYSTEM_ID'] : null);
        $webwayURL = env('WEBWAY_URL', ($variables && array_key_exists('WEBWAY_URL', $variables)) ? $variables['WEBWAY_URL'] : null);
        $webwayToken = env('WEBWAY_TOKEN', ($variables && array_key_exists('WEBWAY_TOKEN', $variables)) ? $variables['WEBWAY_TOKEN'] : null);

        $data = [
            'startSystem' => $startSystem,
            'endSystem' => 30000142,
        ];

        Http::withToken($webwayToken)
            ->withHeaders([
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'User-Agent' => 'evestuff.online evestuff@lol.com',
            ])->post($webwayURL, $data);
    }

    public function notifications(Request $request)
    {
        testNote::create(['text' => $request]);
        test($request, 1);
    }

    public function rc(Request $request)
    {
        // $arry1 = (json_decode(utf8_encode($request), true));
        // $array = json_decode($request, TRUE);
        // dd($array, $arry1, $request[0], $request);

        $inputs = $request->all();
        foreach ($inputs as $input) {
            dd($input);
        }
    }

    public function userinfo()
    {
        $user = Auth::user()->name;
        $id = Auth::id();
        $current = User::find($id);
        dd($user, $id, $current);
    }

    public function test($id)
    {
        $variables = json_decode(base64_decode(getenv('PLATFORM_VARIABLES')), true);
        $url = 'https://recon.gnf.lt/api/structure/' . $id;
        // $dance = env('RECON_TOKEN', "DANCE");
        $dance = env('RECON_TOKEN', ($variables && array_key_exists('RECON_TOKEN', $variables)) ? $variables['RECON_TOKEN'] : 'DANCE2');
        // $dance2 = env('RECON_USER', 'DANCE2');
        $dance2 = env('RECON_USER', ($variables && array_key_exists('RECON_USER', $variables)) ? $variables['RECON_USER'] : 'DANCE2');

        $client = new Client();
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
        if ($data = 'Error, Structure Not Found') {
            // echo "NO STATION";
        } else {
            // echo $dance . " - " . $dance2;
            // echo '<pre>';
            // print_r($data);
            // echo '</pre>';
        }
    }

    public function testNotes()
    {
        $user = Auth::user();
        if ($user->can('super')) {
            Artisan::call('update:notifications');
        } else {
            return null;
        }
    }

    public function testLogs()
    {
        if (Auth::user()->can('super')) {
            // App\Models\NewSystemNode
            // App\Models\NewUserNode
            $opID = 5;
            $operation = NewOperation::where('id', 5)->first();
            $campaigns = $operation->campaign()->get();
            $campaignIDs = $campaigns->pluck('id');
            $systemNode = Activity::where('log_name', 'System Node')
                ->whereIn('properties->attributes->campaign_id', $campaignIDs)
                ->orWHereIn('properties->old->campaign_id', $campaignIDs)
                ->with(['subject', 'causer'])->get();
            $userNode =
                Activity::where('log_name', 'User Node')
                ->whereIn('properties->attributes->node->campaign_id', $campaignIDs)
                ->orWHereIn('properties->old->node->campaign_id', $campaignIDs)
                ->with(['subject', 'causer'])->get();

            $logs = $systemNode->merge($userNode);
            return ['logs' => $logs];
        }
    }

    public function testAPI()
    {
        if (Auth::user()->can('super')) {

            $char = ModelsAuth::where('flag_note', 0)->first();
            if ($char) {
                $charID = $char->char_id;
                $char->flag_note = 1;
            } else {
                ModelsAuth::where('flag_note', 1)->update(['flag_note' => 0]);
                $char = ModelsAuth::where('flag_note', 0)->first();
                $charID = $char->char_id;
                $char->flag_note = 1;
            }

            $refreshToken = refreshToken($charID);
            if ($refreshToken) {
                $data = getNotifications($charID);
                return $data;
            }
        }
    }
}
