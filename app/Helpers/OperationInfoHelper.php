<?php

use App\Events\OperationInfoPageSoloUpdate;
use App\Events\OperationInfoPageUpdate;
use App\Models\DankOperation;
use App\Models\NewCampaign;
use App\Models\NewCampaignOperation;
use App\Models\OperationInfo;
use App\Models\OperationInfoDoctrine;
use App\Models\OperationInfoFleet;
use App\Models\OperationInfoMessage;
use App\Models\OperationInfoMumble;
use App\Models\OperationInfoRecon;
use App\Models\OperationInfoStatus;
use App\Models\OperationInfoSystem;
use App\Models\OperationInfoUser;
use App\Models\OperationInfoUserList;
use App\Models\OperationInfoWatchedSystem;
use App\Models\System;
use App\Models\User;
use Illuminate\Support\Facades\Http;

if (!function_exists('operationInfoAll')) {
    function operationInfoAll()
    {
        $flag = collect([
            'flag' => 1,
            'message' => "fefefefe"
        ]);
        broadcast(new OperationInfoPageUpdate($flag));
        return OperationInfo::with(['status'])->select('id', 'name', 'start', 'status_id', 'link')->get();
    }
}

if (!function_exists('operationInfoSoloBroadcast')) {
    /**
     * Example of documenting multiple possible datatypes for a given parameter

     *
     * @param  int  $flagNumber
     * 2 = Add/Update Solo Operation Info

     */
    function operationInfoSoloBroadcast($id, $flagNumber)
    {
        $message =  operationInfoSolo($id);
        $flag = collect([
            'flag' => $flagNumber,
            'message' => $message
        ]);
        broadcast(new OperationInfoPageUpdate($flag));
    }
}

if (!function_exists('operationInfoSoloDeleteBroadcast')) {
    /**
     * Example of documenting multiple possible datatypes for a given parameter

     *
     * @param  int  $flagNumber
     * 3 = Removes Operation

     */
    function operationInfoSoloDeleteBroadcast($id, $flagNumber)
    {

        $flag = collect([
            'flag' => $flagNumber,
            'message' => $id
        ]);
        broadcast(new OperationInfoPageUpdate($flag));
    }
}

if (!function_exists('operationInfoSolo')) {

    function operationInfoSolo($id)
    {
        return OperationInfo::where('id', $id)
            ->with(['status'])->select('id', 'name', 'start', 'status_id', 'link')->first();
    }
}


if (!function_exists('broadcastOperationInfoUserList')) {
    /**
     * Example of documenting multiple possible datatypes for a given parameter

     *
     * @param  int  $flagNumber
     * 18 = update operation info user list,
     *  @param  int  $opID
     * operation info page id
     */
    function broadcastOperationInfoUserList($opID, $flagNumber)
    {
        $userIDs = OperationInfoUserList::where('operation_info_id', $opID)->pluck('user_id');
        $message = operationInfoUserListAll($userIDs, $opID);
        $flag = collect([
            'flag' => $flagNumber,
            'message' => $message,
            'id' => $opID,
        ]);

        broadcast(new OperationInfoPageSoloUpdate($flag));
    }
}

if (!function_exists('operationInfoUserListAll')) {
    function operationInfoUserListAll($userIDs, $opID)
    {
        return User::whereIn('id', $userIDs)
            ->with(['operationInfoUser' => function ($t) use ($opID) {
                $t->where('operation_info_id', $opID);
            }])->select('id', 'name')->get();
    }
}



if (!function_exists('operationInfoSoloPageBroadcast')) {
    /**
     * Example of documenting multiple possible datatypes for a given parameter

     * @param  int  $id
     * Op ID
     * @param  int  $flagNumber
     * 1 = Add/Update Solo Operation Info

     */
    function operationInfoSoloPageBroadcast($id, $flagNumber)
    {
        $message = operationInfoSoloPagePullTopInfo($id);
        $flag = collect([
            'flag' => $flagNumber,
            'message' => $message,
            'id' => $id
        ]);
        broadcast(new OperationInfoPageSoloUpdate($flag));
    }
}

if (!function_exists('operationInfoSoloPagePullTopInfo')) {
    function operationInfoSoloPagePullTopInfo($id)
    {
        return  OperationInfo::where('id', $id)->first();
    }
}

if (!function_exists('operationInfoSoloPageMessage')) {
    function operationInfoPageMessage($id)
    {
        return  OperationInfoMessage::where('operation_info_id', $id)
            ->with('user:id,name,eve_user_id')
            ->orderBy('id', 'desc')
            ->get();
    }
}

if (!function_exists('operationInfoPageMessageBroadcast')) {
    /**
     * Example of documenting multiple possible datatypes for a given parameter

     *
     *  @param  int  $opID
     * OP ID
     *
     * @param  int  $flagNumber
     * 7 = Add/Update Solo Operation Info Message

     */
    function operationInfoPageMessageBroadcast($opID, $flagNumber)
    {
        $message = operationInfoPageMessage($opID);
        $flag = collect([
            'flag' => $flagNumber,
            'message' => $message,
            'id' => $opID
        ]);
        broadcast(new OperationInfoPageSoloUpdate($flag));
    }
}

if (!function_exists('operationInfoSoloPagePull')) {
    /**
     * Example of documenting multiple possible datatypes for a given parameter

     *
     * @param  int  $flagNumber
     * 2 = Add/Update Solo Operation Info

     */
    function operationInfoSoloPagePull($id)
    {
        $opInfo = OperationInfo::where('id', $id)->first();
        $campaignIDs = NewCampaignOperation::where('operation_id', $opInfo->operation_id)->pluck('campaign_id');
        return  OperationInfo::where('id', $id)->with([
            'messages.user:id,name,eve_user_id',
            'fleets.fc',
            'fleets.boss',
            'fleets.mumble',
            'fleets.doctrine',
            'fleets.alliance',
            'fleets.recons.main:id,eve_user_id,name',
            'fleets.recons.fleetRole',
            'fleets.recons.system',
            'recons.main',
            'recons.status',
            'recons.system',
            'recons.fleet',
            'recons.fleetRole',
            'status',
            'systems:id,system_name,constellation_id,region_id',
            'systems.region:id,region_name',
            'systems.constellation:id,constellation_name',
            'systems.recons',
            'systems.newNodes' => function ($n) use ($campaignIDs) {
                $n->whereIn('campaign_id', $campaignIDs);
            },
            'systems.newNodes.nodeStatus',
            'systems.newNodes.campaign',
            'systems.newNodes.nonePrimeNodeUser.opUser.user',
            'systems.newNodes.nonePrimeNodeUser.nodeStatus',
            'systems.newNodes.primeNodeUser.opUser.user',
            'systems.newNodes.primeNodeUser.nodeStatus',
            'systems.newNodes.system',
            'campaigns.system',
            'campaigns.alliance',
            'operation',
            'dankop',
            'watchSystems:id,system_name,constellation_id,region_id',
            'watchSystems.region:id,region_name',
            'watchSystems.constellation:id,constellation_name',
            'watchSystems.dscan',
        ])->first();
    }
}

if (!function_exists('operationInfoSoloPagePullLink')) {
    /**
     * Example of documenting multiple possible datatypes for a given parameter

     *
     * @param  int  $flagNumber
     * 2 = Add/Update Solo Operation Info

     */
    function operationInfoSoloPagePullLink($link)
    {
        $opInfo = OperationInfo::where('link', $link)->first();
        $campaignIDs = NewCampaignOperation::where('operation_id', $opInfo->operation_id)->pluck('campaign_id');
        return  OperationInfo::where('link', $link)->with([
            'messages.user:id,name,eve_user_id',
            'fleets.fc',
            'fleets.boss',
            'fleets.mumble',
            'fleets.doctrine',
            'fleets.alliance',
            'fleets.recons.main:id,eve_user_id,name',
            'fleets.recons.fleetRole',
            'fleets.recons.system',
            'recons.main',
            'recons.status',
            'recons.system',
            'recons.fleet',
            'recons.fleetRole',
            'status',
            'systems:id,system_name,constellation_id,region_id',
            'systems.region:id,region_name',
            'systems.constellation:id,constellation_name',
            'systems.recons',
            'systems.newNodes' => function ($n) use ($campaignIDs) {
                $n->whereIn('campaign_id', $campaignIDs);
            },
            'systems.newNodes.nodeStatus',
            'systems.newNodes.campaign',
            'systems.newNodes.nonePrimeNodeUser.opUser.user',
            'systems.newNodes.nonePrimeNodeUser.nodeStatus',
            'systems.newNodes.primeNodeUser.opUser.user',
            'systems.newNodes.primeNodeUser.nodeStatus',
            'systems.newNodes.system',
            'campaigns.system',
            'campaigns.alliance',
            'operation',
            'dankop',
            'watchSystems:id,system_name,constellation_id,region_id',
            'watchSystems.region:id,region_name',
            'watchSystems.constellation:id,constellation_name',
            'watchSystems.dscan',

        ])->first();
    }
}


if (!function_exists('OperationInfoSoloSystem')) {

    function OperationInfoSoloSystem($systemID, $opInfoID)
    {
        $opInfo = OperationInfo::where('id', $opInfoID)->first();
        $campaignIDs = NewCampaignOperation::where('operation_id', $opInfo->operation_id)->pluck('campaign_id');
        return  System::where('id', $systemID)->select('id', 'system_name', 'constellation_id', 'region_id')->with([
            'region:id,region_name',
            'constellation:id,constellation_name',
            'recons',
            'newNodes' => function ($n) use ($campaignIDs) {
                $n->whereIn('campaign_id', $campaignIDs);
            },
            'newNodes.nodeStatus',
            'newNodes.campaign',
            'newNodes.nonePrimeNodeUser.opUser.user',
            'newNodes.nonePrimeNodeUser.nodeStatus',
            'newNodes.primeNodeUser.opUser.user',
            'newNodes.primeNodeUser.nodeStatus',
            'newNodes.system',
        ])->first();
    }
}

if (!function_exists('operationInfoSoloSystemBCast')) {
    /**
     * Example of documenting multiple possible datatypes for a given parameter
     * @param  int  $id
     * SYSTEM ID
     *
     *  @param  int  $opID
     * OP ID
     *
     * @param  int  $flagNumber
     * 16 = UpdateSolo System

     */
    function operationInfoSoloSystemBCast($systemID, $flagNumber)
    {

        $OpInfoSystems = OperationInfoSystem::where('system_id', $systemID)->whereNotNull('new_operation_id')->get();
        foreach ($OpInfoSystems as $opInfoSystem) {
            $opID = $opInfoSystem->operation_info_id;
            $message =  OperationInfoSoloSystem($systemID, $opID);
            $flag = collect([
                'flag' => $flagNumber,
                'message' => $message,
                'id' => $opID,
            ]);
            broadcast(new OperationInfoPageSoloUpdate($flag));
        }
    }
}



if (!function_exists('operationInfoUsersAll')) {

    function operationInfoUsersAll()
    {
        return  OperationInfoUser::all();
    }
}

if (!function_exists('operationInfoMumbleAll')) {

    function operationInfoMumbleAll()
    {
        return  OperationInfoMumble::all();
    }
}


if (!function_exists('operationInfoDoctrinesAll')) {

    function operationInfoDoctrinesAll()
    {
        return  OperationInfoDoctrine::orderBy("name")->get();
    }
}


if (!function_exists('operationInfoFleetSolo')) {

    function operationInfoFleetSolo($id)
    {
        return  OperationInfoFleet::where('id', $id)->with([
            'fc',
            'boss',
            'mumble',
            'doctrine',
            'alliance',
            'recons.main:id,eve_user_id,name',
            'recons.fleetRole',
            'recons.system'
        ])->first();
    }
}

if (!function_exists('operationInfoFleetAll')) {

    function operationInfoFleetAll($id)
    {
        return  OperationInfoFleet::where('operation_info_id', $id)->with([
            'fc',
            'boss',
            'mumble',
            'doctrine',
            'alliance',
            'recons.main:id,eve_user_id,name',
            'recons.fleetRole',
            'recons.system'
        ])->get();
    }
}


if (!function_exists('operationInfoSoloPageAllFleetBroadcast')) {
    /**
     * Example of documenting multiple possible datatypes for a given parameter

     *
     *  @param  int  $opID
     * OP ID
     *
     * @param  int  $flagNumber
     * 19 = Update All Fleets

     */
    function operationInfoSoloPageAllFleetBroadcast($opID, $flagNumber)
    {
        $message = operationInfoFleetAll($opID);
        $flag = collect([
            'flag' => $flagNumber,
            'message' => $message,
            'id' => $opID
        ]);
        broadcast(new OperationInfoPageSoloUpdate($flag));
    }
}

if (!function_exists('operationInfoSoloPageAddDankOp')) {
    /**
     * Example of documenting multiple possible datatypes for a given parameter

     *
     *  @param  int  $opID
     * OP ID
     *
     * @param  int  $flagNumber
     * 20 = Add Dank

     */
    function operationInfoSoloPageAddDankOp($opID, $flagNumber)
    {
        $message = DankOperation::where('operation_info_id', $opID)->first();
        $flag = collect([
            'flag' => $flagNumber,
            'message' => $message,
            'id' => $opID
        ]);
        broadcast(new OperationInfoPageSoloUpdate($flag));
    }
}

if (!function_exists('operationInfoSoloPageRemoveDankOp')) {
    /**
     * Example of documenting multiple possible datatypes for a given parameter

     *
     *  @param  int  $opID
     * OP ID
     *
     * @param  int  $flagNumber
     * 22 = remove Dank

     */
    function operationInfoSoloPageRemoveDankOp($opID, $flagNumber)
    {

        $flag = collect([
            'flag' => $flagNumber,
            'id' => $opID
        ]);
        broadcast(new OperationInfoPageSoloUpdate($flag));
    }
}


if (!function_exists('operationInfoSoloPageFleetBroadcast')) {
    /**
     * Example of documenting multiple possible datatypes for a given parameter
     * @param  int  $id
     * Fleet ID
     *
     *  @param  int  $opID
     * OP ID
     *
     * @param  int  $flagNumber
     * 2 = Add/Update Solo Operation Info Fleet

     */
    function operationInfoSoloPageFleetBroadcast($id, $opID, $flagNumber)
    {
        $message = operationInfoFleetSolo($id);
        $flag = collect([
            'flag' => $flagNumber,
            'message' => $message,
            'id' => $opID
        ]);
        broadcast(new OperationInfoPageSoloUpdate($flag));
    }
}

if (!function_exists('operationInfoSoloPageFleetBroadcastDelete')) {
    /**
     * Example of documenting multiple possible datatypes for a given parameter
     * @param  int  $id
     * Fleet ID
     *
     *  @param  int  $opID
     * OP ID
     *
     * @param  int  $flagNumber
     * 6 = Remove Solo Operation Info Fleet

     */
    function operationInfoSoloPageFleetBroadcastDelete($id, $opID, $flagNumber)
    {

        $flag = collect([
            'flag' => $flagNumber,
            'message' => $id,
            'id' => $opID,
        ]);
        broadcast(new OperationInfoPageSoloUpdate($flag));
    }
}



if (!function_exists('operationInfoStatus')) {

    function operationInfoStatus($statusID)
    {
        return  OperationInfoStatus::where('id', $statusID)->first();
    }
}

if (!function_exists('operationInfoStatusBcast')) {
    /**
     * Example of documenting multiple possible datatypes for a given parameter
     * @param  int  $id
     * Status ID
     *
     *  @param  int  $opID
     * OP ID
     *
     * @param  int  $flagNumber
     * 8 = Update Op Status

     */
    function operationInfoStatusBcast($id, $opID, $flagNumber)
    {
        $message =  operationInfoStatus($id);

        $flag = collect([
            'flag' => $flagNumber,
            'message' => $message,
            'id' => $opID,
        ]);
        broadcast(new OperationInfoPageSoloUpdate($flag));
    }
}



if (!function_exists('operationInfoOperation')) {

    function operationInfoOperation($opID)
    {
        $op = OperationInfo::where('id', $opID)->first();
        return  $op->operation;
    }
}

if (!function_exists('operationInfoOperationBcast')) {
    /**
     * Example of documenting multiple possible datatypes for a given parameter
     *
     *
     *  @param  int  $opID
     * OP ID
     *
     * @param  int  $flagNumber
     * 9 = add/remove Hack Operation

     */
    function operationInfoOperationBcast($opID, $flagNumber)
    {
        $message =  operationInfoOperation($opID);

        $flag = collect([
            'flag' => $flagNumber,
            'message' => $message,
            'id' => $opID,
        ]);
        broadcast(new OperationInfoPageSoloUpdate($flag));
    }
}


if (!function_exists('operationInfoCampaigns')) {

    function operationInfoCampaigns($opID)
    {
        $op = OperationInfo::where('id', $opID)->first();
        return  $op->campaigns()->with(['system', 'alliance'])->get();
    }
}



if (!function_exists('operationInfoCampaignsBcast')) {
    /**
     * Example of documenting multiple possible datatypes for a given parameter
     *
     *
     *  @param  int  $opID
     * OP ID
     *
     * @param  int  $flagNumber
     * 10 = Add/Remove Campaigns

     */
    function operationInfoCampaignsBcast($opID, $flagNumber)
    {
        $message =  operationInfoCampaigns($opID);

        $flag = collect([
            'flag' => $flagNumber,
            'message' => $message,
            'id' => $opID,
        ]);
        broadcast(new OperationInfoPageSoloUpdate($flag));
    }
}

if (!function_exists('operationInfoCampaignsSolo')) {

    function operationInfoCampaignsSolo($campaignID)
    {

        return  NewCampaign::where('id', $campaignID)->with(['system', 'alliance'])->first();
    }
}

if (!function_exists('operationInfoCampaignsSoloBcast')) {
    /**
     * Example of documenting multiple possible datatypes for a given parameter
     *
     *
     *  @param  int  $opID
     * OP ID
     *
     * @param  int  $flagNumber
     * 17 = Update Campaigns

     */
    function operationInfoCampaignsSoloBcast($campaignID, $flagNumber)
    {

        $operationIDs = NewCampaignOperation::where('campaign_id', $campaignID)->pluck('operation_id');
        $opInfos = OperationInfo::whereIn('operation_id', $operationIDs)->get();
        foreach ($opInfos as $opInfo) {
            $opID = $opInfo->id;
            $message =  operationInfoCampaignsSolo($campaignID);

            $flag = collect([
                'flag' => $flagNumber,
                'message' => $message,
                'id' => $opID,
            ]);
            broadcast(new OperationInfoPageSoloUpdate($flag));
        }
    }
}


if (!function_exists('operationInfoSystems')) {

    function operationInfoSystems($opID)
    {
        $opInfo = OperationInfo::where('id', $opID)->first();
        $campaignIDs = NewCampaignOperation::where('operation_id', $opInfo->operation_id)->pluck('campaign_id');
        $op = OperationInfo::where('id', $opID)->first();
        $systems = $op->systems()
            ->with([
                'region:id,region_name',
                'constellation:id,constellation_name',
                'recons',
                'newNodes' => function ($n) use ($campaignIDs) {
                    $n->whereIn('campaign_id', $campaignIDs);
                },
                'newNodes.nodeStatus',
                'newNodes.campaign',
                'newNodes.nonePrimeNodeUser.opUser.user',
                'newNodes.nonePrimeNodeUser.nodeStatus',
                'newNodes.primeNodeUser.opUser.user',
                'newNodes.primeNodeUser.nodeStatus',
                'newNodes.system',
            ])
            ->select(['systems.id', 'system_name', 'constellation_id', 'region_id'])
            ->get();
        return $systems;
    }
}



if (!function_exists('operationInfoSystemsBcast')) {
    /**
     * Example of documenting multiple possible datatypes for a given parameter
     *
     *
     *
     *  @param  int  $opID
     * OP ID
     *
     * @param  int  $flagNumber
     * 11 = add/remove systems
     *
     */
    function operationInfoSystemsBcast($opID, $flagNumber)
    {
        $message =  operationInfoSystems($opID);

        $flag = collect([
            'flag' => $flagNumber,
            'message' => $message,
            'id' => $opID,
        ]);
        broadcast(new OperationInfoPageSoloUpdate($flag));
    }
}

if (!function_exists('operationInfoWatchedSystemBcast')) {
    function operationInfoWatchedSystemBcast($opID)
    {
        $info = OperationInfo::where('id', $opID)->with([
            'watchSystems:id,system_name,constellation_id,region_id',
            'watchSystems.region:id,region_name',
            'watchSystems.constellation:id,constellation_name',
            'watchSystems.dscan',
        ])->first();
        $message = $info->watchSystems;
        $flag = collect([
            'flag' => 21,
            'message' => $message,
            'id' => $opID,
        ]);
        broadcast(new OperationInfoPageSoloUpdate($flag));
    }
}



if (!function_exists('operationInfoSoloSystems')) {

    function operationInfoSoloSystems($systemID, $opid)
    {

        $op = OperationInfo::where('id', $opid)->first();
        $systems = $op->systems()
            ->where('systems.id', $systemID)
            ->with([
                'region:id,region_name',
                'constellation:id,constellation_name',
                'recons'
            ])
            ->select(['systems.id', 'system_name', 'constellation_id', 'region_id'])
            ->first();

        return $systems;
    }
}

if (!function_exists('operationInfoSystemsSoloBcast')) {
    /**
     * Example of documenting multiple possible datatypes for a given parameter
     *
     *
     *
     *  @param  int  $opID
     * OP ID
     * @param  int  $systemID
     * ID of System Updated
     * @param  int  $flagNumber
     * 14 = update Solo system ifo
     *
     */
    function operationInfoSystemsSoloBcast($opID, $systemID, $flagNumber)
    {
        $message =  operationInfoSoloSystems($systemID, $opID);

        $flag = collect([
            'flag' => $flagNumber,
            'message' => $message,
            'id' => $opID,
        ]);
        broadcast(new OperationInfoPageSoloUpdate($flag));
    }
}
if (!function_exists('checkUserName')) {

    function checkUserName($name, $opID)
    {
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

                $message = operationInfoUsersAll();
                $flag = collect([
                    'flag' => 3,
                    'message' => $message,
                    'id' => $opID
                ]);
                broadcast(new OperationInfoPageSoloUpdate($flag));
                return $new->id;
            }
        } else {

            $check = OperationInfoUser::where('name', $name)->first();
            return $check->id;
        }
    }
}


if (!function_exists('checkUserNameRecon')) {

    function checkUserNameRecon($name, $mainID, $opID)
    {
        $check = OperationInfoRecon::where('name', $name)->count();
        if ($check == 0) {
            $good = false;
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
                    $good = true;
                    $userIds->push($re[0]['id']);
                }
            }
            if (!$good) {
                return false;
            }
            foreach ($userIds as $userID) {
                $response = Http::withHeaders([
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                    'User-Agent' => 'evestuff.online evestuff@lol.com',
                ])->get('https://esi.evetech.net/latest/characters/' . $userID . '/?datasource=tranquility');
                $res = $response->collect($key = null);
                $new = new OperationInfoRecon();
                $new->id = $userID;
                $new->alliance_id = $res['alliance_id'] ?? null;
                $new->user_id = $mainID;
                $new->corp_id = $res['corporation_id'];
                $new->operation_info_recon_status_id = 1;
                $new->name = $res['name'];
                $new->url = 'https://images.evetech.net/characters/' . $userID . '/portrait?tenant=tranquility&size=64';
                $new->operation_info_id = $opID;
                $new->save();

                $message = operationReconAll();
                $flag = collect([
                    'flag' => 4,
                    'message' => $message,
                    'id' => $opID
                ]);
                broadcast(new OperationInfoPageSoloUpdate($flag));
                operationReconSoloBcast($new->id, 5);
                return true;
            }
        } else {

            $check = OperationInfoRecon::where('name', $name)->first();
            $check->operation_info_id = $opID;
            $check->operation_info_fleet_id = null;
            $check->operation_info_recon_status_id = 1;
            $check->user_id = $mainID;
            $check->save();
            operationReconSoloBcast($check->id, 5);
            return true;
        }
    }
}



if (!function_exists('operationReconAll')) {

    function operationReconAll()
    {
        $data = OperationInfoRecon::with([
            'main:id,name',
            'alliance:id,name,ticker,url',
            'corp:id,name,ticker,url',
            'system:id,system_name',
            'fleetRole',
            'fleet',
            'status'
        ])->get();
        return $data;
    }
}

if (!function_exists('operationReconSolo')) {

    function operationReconSolo($id)
    {
        $data = OperationInfoRecon::where('id', $id)->with([
            'main:id,name',
            'alliance:id,name,ticker,url',
            'corp:id,name,ticker,url',
            'system:id,system_name',
            'fleet',
            'fleetRole',
            'status'
        ])->first();
        return $data;
    }
}

if (!function_exists('operationReconSoloBcast')) {
    /**
     * Example of documenting multiple possible datatypes for a given parameter
     * @param  int  $id
     * Recon User ID
     *
     * @param  int  $flagNumber
     * 5 = Update Recon User

     */
    function operationReconSoloBcast($id, $flagNumber)
    {
        $message = operationReconSolo($id);
        $opID = $message->operation_info_id;
        $flag = collect([
            'flag' => $flagNumber,
            'message' => $message,
            'id' => $opID
        ]);
        broadcast(new OperationInfoPageSoloUpdate($flag));
    }
}

if (!function_exists('operationReconRemoveSoloBcast')) {
    /**
     * Example of documenting multiple possible datatypes for a given parameter
     * @param  int  $id
     * Recon User ID
     *
     *   @param  int  $opID
     * Operation info
     * @param  int  $flagNumber
     * 13 = remove Recon User from operation

     */
    function operationReconRemoveSoloBcast($id, $opID, $flagNumber)
    {
        $message = operationReconSolo($id);
        $flag = collect([
            'flag' => $flagNumber,
            'message' => $message,
            'id' => $opID
        ]);
        broadcast(new OperationInfoPageSoloUpdate($flag));
    }
}


if (!function_exists('operationDoctrineBcast')) {
    /**
     * Example of documenting multiple possible datatypes for a given parameter

     *
     * @param  int  $flagNumber
     * 12 = update fleet doctrins

     */
    function operationDoctrineBcast($flagNumber)
    {

        $OpIDs = OperationInfo::all();
        foreach ($OpIDs as $opID) {
            $id = $opID->id;
            $flag = collect([
                'flag' => $flagNumber,
                'id' => $id,
            ]);
            broadcast(new OperationInfoPageSoloUpdate($flag));
        }
    }
}



if (!function_exists('operationInfoOver')) {
    /**
     * Example of documenting multiple possible datatypes for a given parameter

     * @param  int  $id
     * Op ID
     * @param  int  $flagNumber
     * 15 = closes op and kicks all users

     */
    function operationInfoOver($id, $flagNumber)
    {
        $message = operationInfoSoloPagePullTopInfo($id);
        $flag = collect([
            'flag' => $flagNumber,
            'message' => $message,
            'id' => $id
        ]);
        broadcast(new OperationInfoPageSoloUpdate($flag));
    }
}


if (!function_exists('operationInfoAddDankLink')) {
    /**
     * Example of documenting multiple possible datatypes for a given parameter

     * @param  string  $link
     * Link to operation/fleet page
     * @param  int  $id
     * Op ID
     */
    function operationInfoAddDankLink($link, $id)
    {
        $linkID = basename($link);
        $fleetURL = "https://fleets.apps.gnf.lt/api/v1/coordination/operation/" . $linkID . "/fleets";
        $opURL = "https://fleets.apps.gnf.lt/api/v1/coordination/operation/" . $linkID;

        $variables = json_decode(base64_decode(getenv('PLATFORM_VARIABLES')), true);
        $token = env('DANK_TOKEN', ($variables && array_key_exists('DANK_TOKEN', $variables)) ? $variables['DANK_TOKEN'] : 'null');
        $response = Http::withHeaders([
            'X-ApiKey' => $token,
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'User-Agent' => 'evestuff.online evestuff@lol.com',
        ])->get($fleetURL);

        $fleets =  $response->json();

        $response = Http::withHeaders([
            'X-ApiKey' => $token,
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'User-Agent' => 'evestuff.online evestuff@lol.com',
        ])->get($opURL);

        $operation =  $response->json();

        if (DankOperation::where('dank_id', $linkID)->count() == 0) {
            $new = new DankOperation();
            $new->name = $operation['name'];
            $new->dank_id = $linkID;
            $new->closed_at = $operation['closedAt'] ?? null;
            $new->operation_info_id = $id;
            $new->link = $link;
            $new->save();
            $dankID = $new->id;
        } else {
            $old = DankOperation::where('dank_id', $linkID)->first();
            $old->closed_at = $operation['closedAt'] ?? null;
            $old->name = $operation['name'];
            $dankID = $old->id;
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
                    $checkCustomFleet->dank_operation_id = $dankID;
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
                    $new->dank_operation_id = $dankID;
                    $new->doctrine_id = $docID->id ?? null;
                    $new->operation_info_id = $id;
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

        operationInfoSoloPageAllFleetBroadcast($id, 19);
        operationInfoSoloPageAddDankOp($id, 20);
    }
}

if (!function_exists('operationInfoRemoveDankLink')) {
    /**
     * Example of documenting multiple possible datatypes for a given parameter

     * @param  string  $link
     * Link to operation/fleet page
     * @param  int  $id
     * Op ID
     */
    function operationInfoRemoveDankLink($id)
    {


        $opInfo = OperationInfo::where('id', $id)->first();
        $DankOp = DankOperation::where('operation_info_id', $opInfo->id)->first();
        $DankOp->delete();
        operationInfoSoloPageRemoveDankOp($id, 22);
    }
}
