<?php

namespace App\Http\Controllers;

use App\Events\dScanSoloUpdate;
use App\Events\NotificationChanged;
use App\Events\StagingSystemUpdate;
use App\Jobs\getLocalNamesJob;
use App\Models\Character;
use App\Models\Dscan;
use App\Models\DscanItem;
use App\Models\DscanLocal;
use App\Models\Group;
use App\Models\Item;
use App\Models\Notification;
use App\Models\OperationInfoWatchedSystem;
use App\Models\StagingSystem;
use App\Models\System;
use Http;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Termwind\Components\Dd;

class DscanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        if (Dscan::whereLink($id)->exists()) {
            return getDscanInfo($id);
        } else {
            return loadDscanHistory($id);
        }
    }

    public function checkInputNewNotfication(Request $request, $id)
    {
        $text = $request->text;
        $lines = explode("\n", $text);

        if (count(explode("\t", $lines[0])) == 1) {
            $data = $this->addNewLocal($text, 1);
        } else {

            $data =  $this->addNewDscan($text, 1);
        }

        $dscan_results = Dscan::whereLink($data)->first();
        $notification = Notification::whereId($id)->first();
        $notification->dscan_id = $dscan_results->id;
        $notification->save();
        $message = getSoloNotification($id);
        broadcast(new NotificationChanged($message));
    }

    public function checkInputNew(Request $request)
    {
        $text = $request->text;
        $lines = explode("\n", $text);

        if (count(explode("\t", $lines[0])) == 1) {
            $data = $this->addNewLocal($text);
            return response()->json([
                'link' => $data,
            ]);
        } else {

            $data =  $this->addNewDscan($text);

            return  response()->json([
                'link' => $data,
            ]);
        }
    }

    public function checkInputUpdate(Request $request, $link)
    {
        makeDscanHistoy($link);
        $text = $request->text;
        $lines = explode("\n", $text);
        $data = null;

        if (count(explode("\t", $lines[0])) == 1) {
            $data =  $this->updateLocal($text, $link);
            $this->sendUpdateBoardCasts($data, $link);
            return response()->json([
                'data' => $data,
            ]);
        } else {

            $data =   $this->updateDscan($text, $link);
            $this->sendUpdateBoardCasts($data, $link);
            return   response()->json([
                'data' => $data,
            ]);
        }
    }

    public function sendUpdateBoardCasts($data, $link)
    {


        if ($data['dscan']['type'] == 1) {
            $dscan_results = Dscan::whereLink($data['dscan']['link'])->first();
            $notification = Notification::whereDscanId($dscan_results->id)->first();
            $message = getSoloNotification($notification->id);
            broadcast(new NotificationChanged($message));
        }
        $message = $data['corpsTotal'];
        $flag = collect([
            'id' => $link,
            'flag' => 3,
            'message' => $message,
        ]);
        broadcast(new dScanSoloUpdate($flag))->toOthers();

        $message = $data['allianceTotal'];
        $flag = collect([
            'id' => $link,
            'flag' => 4,
            'message' => $message,
        ]);
        broadcast(new dScanSoloUpdate($flag))->toOthers();

        $message = $data['dscan'];
        $flag = collect([
            'id' => $link,
            'flag' => 5,
            'message' => $message,
        ]);
        broadcast(new dScanSoloUpdate($flag))->toOthers();

        $message = $data['history'];
        $flag = collect([
            'id' => $link,
            'flag' => 6,
            'message' => $message,
        ]);
        broadcast(new dScanSoloUpdate($flag))->toOthers();

        $message = $data['categoryTotals'];
        $flag = collect([
            'id' => $link,
            'flag' => 7,
            'message' => $message,
        ]);
        broadcast(new dScanSoloUpdate($flag))->toOthers();

        $message = $data['groupTotals'];
        $flag = collect([
            'id' => $link,
            'flag' => 8,
            'message' => $message,
        ]);
        broadcast(new dScanSoloUpdate($flag))->toOthers();

        $message = $data['itemTotals'];
        $flag = collect([
            'id' => $link,
            'flag' => 9,
            'message' => $message,
        ]);
        broadcast(new dScanSoloUpdate($flag))->toOthers();

        $message = $data['affiliationTotal'];
        $flag = collect([
            'id' => $link,
            'flag' => 10,
            'message' => $message,
        ]);
        broadcast(new dScanSoloUpdate($flag))->toOthers();
    }

    public function addNewLocal($local, $type = 0)
    {
        $newDscan = new Dscan();
        $newDscan->user_id = Auth::user()->id;
        $newDscan->link = Str::uuid();
        $newDscan->type = $type;
        $newDscan->save();

        $rows = explode("\n", $local);
        $newNames = [];
        foreach ($rows as $key => $row) {
            $pullName =   $rows[$key] = trim($row);
            $char = Character::where('name', $pullName)->whereNotNull('corp_id')->first();
            if (!$char) {
                $newNames[] = $pullName;
            } else {
                $newDscanLocal = new DscanLocal();
                $newDscanLocal->dscan_id = $newDscan->id;
                $newDscanLocal->character_id = $char->id;
                $newDscanLocal->new = true;
                $newDscanLocal->save();
            }
        }


        $newNamesChunks = array_chunk($newNames, 400);

        $responses = [];

        foreach ($newNamesChunks as $chunk) {
            $done = 0;
            $passed = false;

            do {
                $response = Http::withHeaders([
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                    'User-Agent' => 'evestuff.online evestuff@lol.com',
                ])
                    ->withBody(json_encode($chunk), 'application/json')
                    ->post('https://esi.evetech.net/latest/universe/ids/?datasource=tranquility&language=en');

                if ($response->successful()) {
                    $done = 3;
                    $passed = true;
                    $responses[] = $response->json();
                } else {
                    $headers = $response->headers();
                    $sleep = $headers['X-Esi-Error-Limit-Reset'][0];
                    sleep($sleep);
                    $done++;
                }
            } while ($done != 3);
            if (!$passed) {
                $flag = collect([
                    'id' => $newDscan->link,
                    'flag' => 1,
                ]);
                broadcast(new dScanSoloUpdate($flag))->toOthers();
            }
        }
        foreach ($responses as $response) {
            // dd($response, $newNamesChunks);
            $chars = $response['characters'];

            foreach ($chars as $char) {
                $newChar = Character::whereId($char['id'])->first() ?? new Character();
                $newChar->id = $char['id'];
                $newChar->name = $char['name'];
                $newChar->save();
                $newDscanLocal = new DscanLocal();
                $newDscanLocal->dscan_id = $newDscan->id;
                $newDscanLocal->character_id = $newChar->id;
                $newDscanLocal->new = true;
                $newDscanLocal->save();
            }
        }
        $dScanCharIds = DscanLocal::where('dscan_id', $newDscan->id)->pluck('character_id');
        $charIDs = Character::whereIn('id', $dScanCharIds)->whereNull('corp_id')->pluck('id');
        foreach ($charIDs as $charID) {
            getLocalNamesJob::dispatch($charID)->onQueue('alliance');
        }




        if (!$newDscan->system_id) {
            $variables = json_decode(base64_decode(getenv('PLATFORM_VARIABLES')), true);
            $webwayToken = env('WEBWAY_TOKEN', ($variables && array_key_exists('WEBWAY_TOKEN', $variables)) ? $variables['WEBWAY_TOKEN'] : null);
            $webwayURL = env('WEBWAY_URL2', ($variables && array_key_exists('WEBWAY_URL2', $variables)) ? $variables['WEBWAY_URL2'] : null);
            $dScanCharIdsSend = DscanLocal::where('dscan_id', $newDscan->id)->pluck('character_id');
            $res =  Http::withToken($webwayToken)
                ->withHeaders([
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                    'User-Agent' => 'evestuff.online evestuff@lol.com',
                ])->post($webwayURL, $dScanCharIdsSend);

            $info =  $res->json();
            if ($info['status'] == "true") {
                $newDscan->system_id = $info['system_id'];
                $newDscan->addedBySystem = false;
                $newDscan->save();
            }
        }
        newLocal($newDscan->link);
        $this->sendUpdatedDscanInfo($newDscan->link);
        return $newDscan->link;
    }

    public function updateLocal($local, string $id)
    {
        $dScan = Dscan::where('link', $id)->first();
        $dScan->updated_by = Auth::user()->id;
        $dScan->updated_at = now();
        $dScan->save();

        DscanLocal::where('dscan_id', $dScan->id)->update(['updated' => false]);

        $rows = explode("\n", $local);

        $newNames = [];
        foreach ($rows as $key => $row) {
            $pullName =   $rows[$key] = trim($row);
            $char = Character::where('name', $pullName)->whereNotNull('corp_id')->first();
            if (!$char) {
                $newNames[] = $pullName;
            } else {

                $checkForUserInOldScan = DscanLocal::where('character_id', $char->id)->get();
                foreach ($checkForUserInOldScan as $oldScan) {


                    if ($oldScan->dscan_id == $dScan->id) {
                        $oldScan->same = true;
                        $oldScan->new = false;
                        $oldScan->left = false;
                        $oldScan->updated = true;
                        $oldScan->save();
                    }
                }


                if (!DscanLocal::where('dscan_id', $dScan->id)->where('character_id', $char->id)->first()) {
                    $newDscanLocal = new DscanLocal();
                    $newDscanLocal->dscan_id = $dScan->id;
                    $newDscanLocal->character_id = $char->id;
                    $newDscanLocal->updated = true;
                    $newDscanLocal->new = true;
                    $newDscanLocal->save();
                }
            }
        }

        DscanLocal::where('dscan_id', $dScan->id)
            ->where('updated', false)
            ->where('left', true)
            ->delete();

        DscanLocal::where('dscan_id', $dScan->id)
            ->where('updated', false)
            ->update(['left' => true, 'new' => false, 'same' => false, 'updated' => true]);




        $newNamesChunks = array_chunk($newNames, 400);

        $responses = [];

        foreach ($newNamesChunks as $chunk) {
            $done = 0;
            $passed = false;

            do {
                $response = Http::withHeaders([
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                    'User-Agent' => 'evestuff.online evestuff@lol.com',
                ])
                    ->withBody(json_encode($chunk), 'application/json')
                    ->post('https://esi.evetech.net/latest/universe/ids/?datasource=tranquility&language=en');

                if ($response->successful()) {
                    $done = 3;
                    $passed = true;
                    $responses[] = $response->json();
                } else {
                    $headers = $response->headers();
                    $sleep = $headers['X-Esi-Error-Limit-Reset'][0];
                    sleep($sleep);
                    $done++;
                }
            } while ($done != 3);
            if (!$passed) {
                $flag = collect([
                    'id' => $dScan->link,
                    'flag' => 1,
                ]);
                broadcast(new dScanSoloUpdate($flag))->toOthers();
            }
        }
        foreach ($responses as $response) {
            $chars = $response['characters'];

            foreach ($chars as $char) {
                $newChar = Character::whereId($char['id'])->first() ?? new Character();
                $newChar->id = $char['id'];
                $newChar->name = $char['name'];
                $newChar->save();
                $newDscanLocal = new DscanLocal();
                $newDscanLocal->dscan_id = $dScan->id;
                $newDscanLocal->character_id = $newChar->id;
                $newDscanLocal->updated = true;
                $newDscanLocal->new = true;
                $newDscanLocal->save();
            }
        }
        $dScanCharIDs = DscanLocal::where('dscan_id', $dScan->id)->pluck('character_id');
        $charIDs = Character::whereIn('id', $dScanCharIDs)->whereNull('corp_id')->pluck('id');
        foreach ($charIDs as $charID) {
            getLocalNamesJob::dispatch($charID)->onQueue('alliance');
        }

        if (!$dScan->system_id) {
            $variables = json_decode(base64_decode(getenv('PLATFORM_VARIABLES')), true);
            $webwayToken = env('WEBWAY_TOKEN', ($variables && array_key_exists('WEBWAY_TOKEN', $variables)) ? $variables['WEBWAY_TOKEN'] : null);
            $webwayURL = env('WEBWAY_URL2', ($variables && array_key_exists('WEBWAY_URL2', $variables)) ? $variables['WEBWAY_URL2'] : null);
            $dScanCharIdsSend = DscanLocal::where('dscan_id', $dScan->id)->pluck('character_id');
            $res =  Http::withToken($webwayToken)
                ->withHeaders([
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                    'User-Agent' => 'evestuff.online evestuff@lol.com',
                ])->post($webwayURL, $dScanCharIdsSend);

            $info =  $res->json();
            if ($info['status'] == "true") {
                $dScan->system_id = $info['system_id'];
                $dScan->addedBySystem = false;
                $dScan->save();
            }
        }
        newLocal($dScan->link);
        $this->sendUpdatedDscanInfo($dScan->link);

        return getDscanInfo($dScan->link);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function addNewDscan($dscan_results, $type = 0)
    {


        $newDscan = new Dscan();
        $newDscan->user_id = Auth::user()->id;
        $newDscan->type = $type;
        $newDscan->link = Str::uuid();
        $newDscan->save();

        $lines  = explode("\n", $dscan_results);
        $data = array();
        foreach ($lines as $line) {
            $data[] = explode("\t", $line);
        }

        foreach ($data as $row) {
            $on = false;
            $distance_parts = explode(" ", $row[3]);
            $row[3] = str_replace(',', '', $distance_parts[0]);
            if (count($distance_parts) == 2) {
                $row[] = $distance_parts[1];
            } else {
                $row[] = 'AU';
                if (empty($row[3]) || $row[3] == "-") {
                    $row[3] = 1;
                }
            }
            $newDscanItem = new DscanItem();
            $newDscanItem->dscan_id = $newDscan->id;
            $newDscanItem->distance_value = $row[3];
            $newDscanItem->distance_unit = $row[4];
            $newDscanItem->item_id = $row[0];
            $newDscanItem->name = $row[1];
            if ($newDscanItem->distance_unit == "km" && $newDscanItem->distance_value <= 8000) {
                $on = true;
            }

            if ($newDscanItem->distance_unit == "m") {
                $on = true;
            }
            $newDscanItem->on_grid = $on;
            $newDscanItem->new = true;
            $newDscanItem->save();
            $item = Item::whereId($row[0])->first();
            $group = Group::whereId($item->group_id)->first();
            if (!$newDscan->system_id || !$newDscan->addedBySystem) {
                $groupName = $item->group->name;
                if ($groupName == "Sun") {
                    $systemName = explode(" - ", $row[1])[0];
                    $system = System::where('system_name', $systemName)->first();
                    $newDscan->system_id = $system->id;
                    $newDscan->addedBySystem = true;
                    $newDscan->save();
                }

                if (
                    $group->id == 1406 ||
                    $group->id == 1876 ||
                    $group->id == 1657 ||
                    $group->id == 1404 ||
                    $group->id == 15
                ) {
                    $systemName = explode(" - ", $row[1])[0];
                    $system = System::where('system_name', $systemName)->first();
                    $newDscan->system_id = $system->id;
                    $newDscan->addedBySystem = true;
                    $newDscan->save();
                }
            }
        }

        newDscan($newDscan->link);
        $this->sendUpdatedDscanInfo($newDscan->link);
        return  $newDscan->link;
    }

    /**
     * Update the specified resource in storage.
     */
    public function updateDscan($dscan_results, $link)
    {


        $dScan = Dscan::whereLink($link)->first();
        $dScan->updated_by = Auth::user()->id;
        $dScan->save();



        $lines  = explode("\n", $dscan_results);
        $data = array();
        foreach ($lines as $line) {
            $data[] = explode("\t", $line);
        }


        DscanItem::where('dscan_id', $dScan->id)->update(['updated' => false]);

        foreach ($data as $row) {
            $on = false;
            $distance_parts = explode(" ", $row[3]);
            $row[3] = str_replace(',', '', $distance_parts[0]);
            if (count($distance_parts) == 2) {
                $row[] = $distance_parts[1];
            } else {
                $row[] = 'AU';
                if (empty($row[3]) || $row[3] == "-") {
                    $row[3] = 1;
                }
            }

            $dScanItem = DscanItem::where('dscan_id', $dScan->id)->where('item_id', $row[0])->where('updated', false)->first();
            if ($dScanItem) {
                $dScanItem->updated = true;
                $dScanItem->new = false;
                $dScanItem->same = true;
                $dScanItem->distance_value = $row[3];
                $dScanItem->distance_unit = $row[4];

                if ($dScanItem->distance_unit == "km" && $dScanItem->distance_value <= 8000) {
                    $on = true;
                }

                if ($dScanItem->distance_unit == "m") {
                    $on = true;
                }
                $dScanItem->on_grid = $on;
                $dScanItem->save();
                $item = Item::whereId($row[0])->first();
                $group = Group::whereId($item->group_id)->first();
            } else {
                $newDscanItem = new DscanItem();
                $newDscanItem->dscan_id = $dScan->id;
                $newDscanItem->item_id = $row[0];
                $newDscanItem->distance_value = $row[3];
                $newDscanItem->distance_unit = $row[4];
                $newDscanItem->name = $row[1];
                $newDscanItem->updated = true;
                if ($newDscanItem->distance_unit == "km" && $newDscanItem->distance_value <= 8000) {
                    $on = true;
                }

                if ($newDscanItem->distance_unit == "m") {
                    $on = true;
                }
                $newDscanItem->on_grid = $on;
                $newDscanItem->new = true;
                $newDscanItem->save();
            }

            $item = Item::whereId($row[0])->first();
            $group = Group::whereId($item->group_id)->first();
            if (!$dScan->system_id || !$dScan->addedBySystem) {
                $groupName = $item->group->name;
                if ($groupName == "Sun") {
                    $systemName = explode(" - ", $row[1])[0];
                    $system = System::where('system_name', $systemName)->first();
                    $dScan->system_id = $system->id;
                    $dScan->addedBySystem = true;
                    $dScan->save();
                }

                if (
                    $group->id == 1406 ||
                    $group->id == 1876 ||
                    $group->id == 1657 ||
                    $group->id == 1404 ||
                    $group->id == 15
                ) {
                    $systemName = explode(" - ", $row[1])[0];
                    $system = System::where('system_name', $systemName)->first();
                    $dScan->system_id = $system->id;
                    $dScan->addedBySystem = true;
                    $dScan->save();
                }
            }
        }

        DscanItem::where('dscan_id', $dScan->id)
            ->where('updated', false)
            ->where(function ($query) {
                $query->where('new', true)
                    ->orWhere('same', true);
            })
            ->update([
                'new' => false,
                'same' => false,
                'left' => true,
                'updated' => true
            ]);

        DscanItem::where('dscan_id', $dScan->id)
            ->where('updated', false)
            ->where('left', true)
            ->delete();



        newDscan($dScan->link);

        $this->sendUpdatedDscanInfo($dScan->link);


        return  $this->show($dScan->link);
    }




    public function updateLocalNamePull($linkID)
    {
        $dScan = Dscan::whereLink($linkID)->first();
        $dScanCharIDs = DscanLocal::where('dscan_id', $dScan->id)->pluck('character_id');
        $charIDs = Character::whereIn('id', $dScanCharIDs)->whereNull('corp_id')->pluck('id');
        foreach ($charIDs as $charID) {
            getLocalNamesJob::dispatch($charID)->onQueue('alliance');
        }
    }

    public function sendUpdatedDscanInfo($linkID)
    {
        $dScan = Dscan::whereLink($linkID)->first();
        $dscanSystemID = $dScan->system_id;
        $stagingSystems = StagingSystem::whereSystemId($dscanSystemID)->with(
            'system:id,constellation_id,system_name',
            'system.constellation:id,constellation_name,region_id',
            'system.constellation.region:id,region_name',
            'system.webway',
            'dscan'
        )->get();
        foreach ($stagingSystems as $stagingSystem) {
            $flag = collect([
                'flag' => 1,
                'message' => $stagingSystem
            ]);
            broadcast(new StagingSystemUpdate($flag));
        }

        $watchedSystems = OperationInfoWatchedSystem::whereIn('system_id', [$dscanSystemID])->get();
        foreach ($watchedSystems as $watchedSystem) {
            $opID = $watchedSystem->operation_info_id;
            operationInfoWatchedSystemBcast($opID);
        }
    }




    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
