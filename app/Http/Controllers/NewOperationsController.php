<?php

namespace App\Http\Controllers;

use App\Events\OperationOwnUpdate;
use App\Models\Constellation;
use App\Models\NewCampaign;
use App\Models\NewCampaignOperation;
use App\Models\NewOperation;
use App\Models\OperationUser;
use App\Models\OperationUserList;
use App\Models\Region;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class NewOperationsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();

        if ($user->can('access_campaigns')) {
            $data = NewOperation::where('solo', 1)
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
                ->get();
        } else {
            $data = NewOperation::where('solo', 1)
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
                ->get();
        }

        $regionIDs = collect();
        $contellationIDs = collect();
        $regions = NewOperation::where('solo', 1)->with('campaign.constellation.region')->get();
        foreach ($regions as $r) {
            $regionIDs->push($r->campaign[0]->constellation->region->id);
            $contellationIDs->push($r->campaign[0]->constellation->id);
        }

        $uRegionIDs = $regionIDs->unique();
        $uContellationID = $contellationIDs->unique();
        $regionList = Region::whereIn('id', $uRegionIDs)->select(['id as value', 'region_name as text'])->orderBy('region_name', 'asc')->get();
        $constellationList = Constellation::whereIn('id', $uContellationID)->select(['id as value', 'constellation_name as text'])->orderBy('constellation_name', 'asc')->get();



        return [
            'solooplist' => $data,
            'regionList' => $regionList,
            'constellationList' => $constellationList
        ];
    }

    public function sendAddCharOverlay($opID, $type)
    {
        broadcastAllCharOverlay(1, $opID, $type);
    }


    public function operationList()
    {
        $data = NewOperation::where('status', 1)->get();
        return ['operations' => $data];
    }

    public function changeReadyOnly(Request $request, $opID)
    {
        $user = Auth::user();
        if ($user->can('edit_read_only')) {
            $op = NewOperation::where('id', $opID)->first();
            $op->read_only = $request->read_only;
            $op->log_helper = 1;
            $op->save();
            broadcastOperationSetReadOnly($opID, 2, $request->read_only);
        } else {
            return null;
        }
    }

    public function makeNewOperation(Request $request)
    {
        $user = Auth::user();
        if ($user->can('access_multi_campaigns')) {
            $uuid = Str::uuid();

            $newOp = new NewOperation();
            $newOp->title = $request->title;
            $newOp->link = $uuid;
            $newOp->solo = 0;
            $newOp->status = 1;
            $newOp->save();


            $campaignIDs = $request->picked;
            foreach ($campaignIDs as $campaignID) {
                $new = new NewCampaignOperation();
                $new->campaign_id = $campaignID['value'];
                $new->operation_id = $newOp->id;
                $new->save();
            }

            broadcastCustomOperationSolo($newOp->id, 1);
        } else {
            return null;
        }
    }

    public function edit(Request $request)
    {
        $n = NewCampaignOperation::where('operation_id', $request->OpID)
            ->whereNotIn('campaign_id', $request->picked)->get();

        foreach ($n as $n) {
            $n->delete();
        }

        foreach ($request->picked as $campaignID) {
            $count = NewCampaignOperation::where('operation_id', $request->OpID)->where('campaign_id', $campaignID)->count();
            if ($count == 0) {
                $new = new NewCampaignOperation();
                $new->campaign_id = $campaignID;
                $new->operation_id = $request->OpID;
                $new->save();
            }
        }
        broadcastCustomOperationSolo($request->OpID, 2);
    }

    public function updatePriority(Request $request, $id)
    {
        $user = Auth::user();
        if ($user->can('edit_hack_priority')) {
            $operation = NewOperation::where('id', $id)->first();
            $operation->priority = $request->priority;
            $operation->log_helper = 2;
            $operation->save();
        }
    }

    public function getCustomOperationList()
    {
        $ops = NewOperation::where('solo', 0)->with(['campaign.system', 'campaign.alliance'])->get();

        return ['operations' => $ops];
    }

    public function getInfo($id)
    {
        $user = Auth::user();
        $data = NewOperation::where('link', $id)
            ->with([
                'campaign',
                'campaign.status',
                'campaign.constellation:id,constellation_name,region_id',
                'campaign.constellation.region:id,region_name',
                'campaign.alliance:id,name,ticker,standing,url,color',
                'campaign.system:id,system_name,adm',
            ])
            ->first();

        $operationsID = NewOperation::where('link', $id)->pluck('id');
        $campaignIDs = NewCampaignOperation::where('operation_id', $operationsID)->pluck('campaign_id');
        // $contellationIDs = NewCampaign::whereIn('id', $campaignIDs)->where('status_id', [2, 5])->pluck('20000646');
        $contellationIDs = NewCampaign::whereIn('id', $campaignIDs)->pluck('constellation_id');
        $contellationIDs = $contellationIDs->unique();
        $userIDs = OperationUserList::where('operation_id', $operationsID)->pluck('user_id');
        $systems = systemsAll($contellationIDs, $operationsID);
        $opUsers = opUserAll($operationsID);
        $ownChars = ownUserAll(Auth::id());
        if ($user->can('view_campaign_members')) {
            $userList = userListAll($userIDs, $operationsID);
        } else {
            $userList = null;
        }

        return [
            'data' => $data,
            'systems' => $systems,
            'opUsers' => $opUsers,
            'ownChars' => $ownChars,
            'userList' => $userList,
        ];
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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


    public function toggleWindow($toggle, $opID)
    {
        $flag = collect([
            'flag' => 8,
            'op_id' => $opID,
            'id' => Auth::id(),
            'type' => $toggle,
        ]);

        broadcast(new OperationOwnUpdate($flag));
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $opToDelete = NewOperation::where('id', $id)->first();
        $campaignToDelete = NewCampaignOperation::where('operation_id', $id)->get();

        foreach ($campaignToDelete as $a) {
            $a->delete();
        }
        $opToDelete->delete();

        $operationUsers = OperationUser::where('operation_id', $id)->get();

        foreach ($operationUsers as $opuser) {
            $opuser->update([
                'operation_id' => null,
                'user_status_id' => 1,
                'system_id' => null,
            ]);

            broadcastuserOwnSolo($opuser->id, $opuser->user_id, 3, $id);
        }

        broadcastCustomOperationDeleteSolo($id, 3);
    }
}
