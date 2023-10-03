<?php

use App\Events\CustomOperationPageUpdate;
use App\Events\OperationAdminUpdate;
use App\Events\OperationOwnUpdate;
use App\Events\OperationUpdate;
use App\Events\SoloOperationUpdate;
use App\Models\NewCampaign;
use App\Models\NewCampaignOperation;
use App\Models\NewCampaignSystem;
use App\Models\NewOperation;
use App\Models\OperationUserList;

if (!function_exists('broadcastsystemSolo')) {
    /**
     * Example of documenting multiple possible datatypes for a given parameter

     *
     * @param  int  $flagNumber
     * 4 = updates system info
     * 5 = remove char from ops table
     * 7 = update campaign system
     */
    function broadcastsystemSolo($systemID, $flagNumber)
    {
        $campaignIDs = NewCampaignSystem::where('system_id', $systemID)->pluck('new_campaign_id');
        $obIDS = NewCampaignOperation::whereIn('campaign_id', $campaignIDs)->pluck('operation_id');

        foreach ($obIDS as $op) {
            $message = systemSolo($systemID, $op);
            $flag = collect([
                'flag' => $flagNumber,
                'message' => $message,
                'id' => $op,
            ]);
            broadcast(new OperationUpdate($flag));
        }
    }
}

if (!function_exists('broadcastCampaignSolo')) {
    function broadcastCampaignSolo($campaignID, $opID, $flagNumber)
    {
        $message = campaignSolo($campaignID);
        $flag = collect([
            'flag' => $flagNumber,
            'id' => $opID,
            'message' => $message,
        ]);

        broadcast(new OperationUpdate($flag));
    }
}

if (!function_exists('broadcastuserSolo')) {
    /**
     * Example of documenting multiple possible datatypes for a given parameter

     *
     * @param  int  $flagNumber
     * 4 = updates system info
     * 5 = remove char from ops table
     * 6 = update op char table
     */
    function broadcastuserSolo($opID, $opUserID, $flagNumber)
    {
        $message = opUserSolo($opID, $opUserID);
        // dd($message,$opID, $opUserID, $flagNumber);
        $flag = collect([
            'flag' => $flagNumber,
            'message' => $message,
            'id' => $opID,
        ]);

        broadcast(new OperationUpdate($flag));
    }
}

if (!function_exists('broadcastOperationRefresh')) {
    /**
     * Example of documenting multiple possible datatypes for a given parameter

     *
     * @param  int  $flagNumber
     * 3 = refresh operation info
     * 8 = update camaping page status
     */
    function broadcastOperationRefresh($opID, $campaignID, $flagNumber)
    {
        $message = NewOperation::where('id', $opID)
            ->with([
                'campaign' => function ($q) use ($campaignID) {
                    $q->where('campaign_id', $campaignID);
                },
                'campaign.status',
                'campaign.constellation:id,constellation_name,region_id',
                'campaign.constellation.region:id,region_name',
                'campaign.alliance:id,name,ticker,standing,url,color',
                'campaign.system:id,system_name,adm',
            ])
            ->first();

        $flag = collect([
            'flag' => $flagNumber,
            'message' => $message,
            'id' => $opID,
        ]);

        broadcast(new OperationUpdate($flag));

        $campaignIDs = NewCampaignOperation::where('operation_id', $opID)->pluck('campaign_id');
        $contellationIDs = NewCampaign::whereIn('id', $campaignIDs)->pluck('constellation_id');
        $contellationIDs = $contellationIDs->unique();
        $systems = systemsAll($contellationIDs, $opID);

        $flag = collect([
            'flag' => 9,
            'message' => $systems,
            'id' => $opID,
        ]);

        broadcast(new OperationUpdate($flag));
    }
}

if (!function_exists('broadcastOperationSetReadOnly')) {
    /**
     * Example of documenting multiple possible datatypes for a given parameter

     *
     * @param  int  $flagNumber
     * 2 = set Read Only
     */
    function broadcastOperationSetReadOnly($opID, $flagNumber, $message)
    {
        $flag = collect([
            'flag' => $flagNumber,
            'message' => $message,
            'id' => $opID,
        ]);

        broadcast(new OperationUpdate($flag));
    }
}

if (!function_exists('broadcastOperationUserList')) {
    /**
     * Example of documenting multiple possible datatypes for a given parameter

     *
     * @param  int  $flagNumber
     * 1 = update operation user list,
     */
    function broadcastOperationUserList($opID, $flagNumber)
    {
        $userIDs = OperationUserList::where('operation_id', $opID)->pluck('user_id');
        $message = userListAll($userIDs, $opID);
        $flag = collect([
            'flag' => $flagNumber,
            'op_id' => $opID,
            'message' => $message,
            'id' => $opID,
        ]);

        broadcast(new OperationAdminUpdate($flag));
    }
}

if (!function_exists('broadcastuserOwnSolo')) {

    /**
     * Example of documenting multiple possible datatypes for a given parameter

     *
     * @param  int  $flagNumber
     * 3 = Add/Update Own Char info -
     * 5 = RemoveChar -
     */
    function broadcastuserOwnSolo($opUserID, $userID, $flagNumber, $opID)
    {
        $message = ownUsersolo($opUserID);
        $flag = collect([
            'flag' => $flagNumber,
            'op_id' => $opID,
            'userid' => $opUserID,
            'id' => $userID,
            'message' => $message,
        ]);

        broadcast(new OperationOwnUpdate($flag));
    }
}

if (!function_exists('broadcastAllCharOverlay')) {
    /**
     * Example of documenting multiple possible datatypes for a given parameter

     *
     * @param  int  $flagNumber
     * 1 = Open Add Char overlay -
     *  @param  int  $type
     * 1 = Normal message -
     * 2 = read only message
     */
    function broadcastAllCharOverlay($flagNumber, $opID, $type)
    {
        $users = OperationUserList::where('operation_id', $opID)
            ->withCount(['ownUsers' => function ($query) use ($opID) {
                $query->where('operation_id', $opID);
            }])->get();

        foreach ($users as $user) {
            if ($user->own_users_count == 0) {
                $userID = $user->user_id;

                $flag = collect([
                    'flag' => $flagNumber,
                    'op_id' => $opID,
                    'id' => $userID,
                    'type' => $type,
                ]);

                broadcast(new OperationOwnUpdate($flag));
            }
        }
    }
}

if (!function_exists('broadcastCustomOperationSolo')) {
    /**
     * Example of documenting multiple possible datatypes for a given parameter

     *
     * @param  int  $flagNumber
     * 1 = Add Custom Operation To list -
     * 2 = Update Custom Operation On List -
     * 3 = Delete CUstom Operation from list -
     */
    function broadcastCustomOperationSolo($opID, $flagNumber)
    {
        $message = customOperationSolo($opID);
        $flag = collect([
            'flag' => $flagNumber,
            'message' => $message,
        ]);
        broadcast(new CustomOperationPageUpdate($flag));
    }
}

/**
 * Example of documenting multiple possible datatypes for a given parameter

 *
 * @param  int  $flagNumber
 * 3 = Delete CUstom Operation from list -
 */
if (!function_exists('broadcastCustomOperationDeleteSolo')) {
    function broadcastCustomOperationDeleteSolo($opID, $flagNumber)
    {
        $flag = collect([
            'flag' => $flagNumber,
            'message' => $opID,
        ]);
        broadcast(new CustomOperationPageUpdate($flag));
    }
}

if (!function_exists('broadcastSoloOpSoloOp')) {
    /**
     * Example of documenting multiple possible datatypes for a given parameter

     *
     * @param  int  $flagNumber
     * 1 = Update solo SoloOp Table -
     */
    function broadcastSoloOpSoloOp($flagNumber, $opID)
    {
        $message = NewOperation::where('solo', 1)
            ->where('id', $opID)
            ->with([
                'campaign',
                'campaign.status',
                'campaign.constellation:id,constellation_name,region_id',
                'campaign.constellation.region:id,region_name',
                'campaign.alliance:id,name,ticker,standing,url,color',
                'campaign.system:id,system_name,adm',
                'campaign.system.webway' => function ($t) {
                    $t->where('permissions', 1);
                },
                'campaign.structure:id,item_id,age',
            ])
            ->first();

        $flag = collect([
            'flag' => $flagNumber,
            'message' => $message,
        ]);
        broadcast(new SoloOperationUpdate($flag));
    }
}
