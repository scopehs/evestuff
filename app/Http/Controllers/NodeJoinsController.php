<?php

namespace App\Http\Controllers;

use App\Events\CampaignSystemUpdate;
use App\Events\CampaignUserUpdate;
use App\Events\NodeJoinDelete;
use App\Events\NodeJoinNew;
use App\Events\NodeJoinUpdate;
use App\Models\CampaignSolaSystem;
use App\Models\CampaignSystem;
use App\Models\CampaignSystemRecords;
use App\Models\CampaignSystemStatus;
use App\Models\CampaignUser;
use App\Models\CampaignUserRecords;
use App\Models\NodeJoin;
use App\Models\User;
use Illuminate\Http\Request;

class NodeJoinsController extends Controller
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

    public function removeCharForNode(Request $request, $id, $campid)
    {
        $status = null;
        $nodes = NodeJoin::where('campaign_system_id', $id)->get();

        if ($nodes->count() > 0) {
            if ($request['campaign_system_status_id'] == 4 || $request['campaign_system_status_id'] == 5) {
                foreach ($nodes as $node) {
                    $flag = collect([
                        'joinNodeID' => $node->id,
                        'id' => $campid,
                    ]);
                    broadcast(new NodeJoinDelete($flag));

                    $c = CampaignUser::where('id', $node->campaign_user_id)->get();
                    foreach ($c as $c) {
                        $c->update(['campaign_system_id' => null, 'status_id' => 3]);
                    }
                    $message = CampaignUserRecords::where('id', $node->campaign_user_id)->first();
                    $flag = null;
                    $flag = collect([
                        'message' => $message,
                        'id' => $campid,
                    ]);
                    broadcast(new CampaignUserUpdate($flag));

                    $campaign_system_status_id = $node->campaign_system_status_id;
                    $node->delete();
                }

                $CampaignSystem = CampaignSystem::where('id', $id)->first();
                $c = CampaignUser::where('id', $CampaignSystem->campaign_user_id)->get();
                foreach ($c as $c) {
                    $c->update(['campaign_system_id' => null, 'status_id' => 3]);
                }
                $message = CampaignUserRecords::where('id', $CampaignSystem->campaign_user_id)->first();
                $flag = null;
                $flag = collect([
                    'message' => $message,
                    'id' => $campid,
                ]);
                broadcast(new CampaignUserUpdate($flag));
                $CampaignSystem->update(['campaign_user_id' => null, 'campaign_system_status_id' => $request['campaign_system_status_id'], 'node_join_count' => 0]);
                $message = CampaignSystemRecords::where('id', $CampaignSystem->id)->first();
                $flag = null;
                $flag = collect([
                    'message' => $message,
                    'id' => $campid,
                ]);
                broadcast(new CampaignSystemUpdate($flag));

                return;
            }

            if ($nodes->where('campaign_system_status_id', 3)->count() > 0) {
                $status = 1;
                $node = $nodes->where('campaign_system_status_id', 3)->first();
            } elseif ($nodes->where('campaign_system_status_id', 2)->count() > 0) {
                $status = 2;
                $node = $nodes->where('campaign_system_status_id', 2)->first();
            } else {
                $node = $nodes->first();
            }

            $flag = collect([
                'joinNodeID' => $node->id,
                'id' => $campid,
            ]);
            broadcast(new NodeJoinDelete($flag));

            $user_id = $node->campaign_user_id;
            $campaign_system_status_id = $node->campaign_system_status_id;
            $CampaignSystem = CampaignSystem::where('id', $id)->first();
            $count = $CampaignSystem->node_join_count;
            if ($count == 1) {
                $count = 0;
            } else {
                $count = $count - 1;
            }

            $c = CampaignUser::where('id', $CampaignSystem->campaign_user_id)->get();
            foreach ($c as $c) {
                $c->update(['campaign_system_id' => null, 'status_id' => 3]);
            }
            $message = CampaignUserRecords::where('id', $CampaignSystem->campaign_user_id)->first();
            $flag = null;
            $flag = collect([
                'message' => $message,
                'id' => $campid,
            ]);
            broadcast(new CampaignUserUpdate($flag));

            if ($status == 1) {
                $CampaignSystem->update(['campaign_user_id' => $user_id, 'campaign_system_status_id' => $campaign_system_status_id, 'node_join_count' => $count]);
            } else {
                $CampaignSystem->update(['campaign_user_id' => $user_id, 'campaign_system_status_id' => $campaign_system_status_id, 'node_join_count' => $count, 'input_time' => null, 'base_time' => null, 'end_time' => null]);
            }

            $message = CampaignSystemRecords::where('id', $CampaignSystem->id)->first();
            $flag = null;
            $flag = collect([
                'message' => $message,
                'id' => $campid,
            ]);
            broadcast(new CampaignSystemUpdate($flag));

            $node->delete();
        } else {
            if ($request['campaign_system_status_id'] == null) {
                $campaign_system_status_id = 1;
            } else {
                $campaign_system_status_id = $request['campaign_system_status_id'];
            }
            $user_id = CampaignSystem::where('id', $id)->value('campaign_user_id');
            $c = CampaignUser::where('id', intval($user_id))->get();
            foreach ($c as $c) {
                $c->update(['campaign_system_id' => null, 'status_id' => 3]);
            }
            $c = CampaignSystem::where('id', $id)->get();
            foreach ($c as $c) {
                $c->update($request->all());
            }
            $c = CampaignSystem::where('id', $id)->get();
            foreach ($c as $c) {
                $c->update(['campaign_system_status_id' => $campaign_system_status_id, 'base_time' => null, 'input_time' => null, 'end_time' => null]);
            }
            $flag = null;
            $message = CampaignUserRecords::where('id', $user_id)->first();
            $flag = collect([
                'message' => $message,
                'id' => $campid,
            ]);
            broadcast(new CampaignUserUpdate($flag));
            $flag = null;
            $message = CampaignSystemRecords::where('id', $id)->first();
            $flag = collect([
                'message' => $message,
                'id' => $campid,
            ]);
            broadcast(new CampaignSystemUpdate($flag));
        }

        //done - Just waiting to finish rest before removing this call//
        // $flag = null;
        // $flag = collect([
        //     'flag' => 3,
        //     'id' => $campid
        // ]);
        // broadcast(new CampaignSystemUpdate($flag));
    }

    public function deleteExtraNode($id, $campid)
    {
        $node = NodeJoin::where('id', $id)->first();

        $flag = null;
        $flag = collect([
            'joinNodeID' => $id,
            'id' => $campid,
        ]);
        broadcast(new NodeJoinDelete($flag));

        $CampaignSystem = CampaignSystem::where('id', $node->campaign_system_id)->first();
        $count = $CampaignSystem->node_join_count - 1;
        $CampaignSystem->update(['node_join_count' => $count]);

        $message = CampaignSystemRecords::where('id', $node->campaign_system_id)->first();
        $flag = collect([
            'message' => $message,
            'id' => $campid,
        ]);
        broadcast(new CampaignSystemUpdate($flag));

        $CampaignUser = CampaignUser::where('id', $node->campaign_user_id)->first();
        $CampaignUser->update(['campaign_system_id' => null, 'status_id' => 3]);

        $message = CampaignUserRecords::where('id', $node->campaign_user_id)->first();
        $flag = null;
        $flag = collect([
            'message' => $message,
            'id' => $campid,
        ]);
        broadcast(new CampaignUserUpdate($flag));

        $node->delete();
    }

    public function addCharToNodeAdmin(Request $request, $id, $campid)
    {
        $campaignSystemID = $request['campaignSystemID'];
        $campaignUserID = $request['campaignUserID'];

        $campaignSystem = CampaignSystem::where('id', $id)->first();
        if ($campaignSystem->campaign_user_id == null) {
            $campaignSystem->update(['campaign_user_id' => $campaignUserID]);
        } else {
            $new = NodeJoin::create(['campaign_id' => $campid, 'campaign_system_id' => $id, 'campaign_user_id' => $campaignUserID, 'campaign_system_status_id' => 1]);
            $message = nodeJoinRecords($new->id);
            $flag = null;
            $flag = collect([
                'message' => $message,
                'id' => $campid,
            ]);
            broadcast(new NodeJoinNew($flag))->toOthers();

            $count = $campaignSystem->node_join_count + 1;
            $campaignSystem->update(['node_join_count' => $count]);
        }

        $flag = null;
        $message = CampaignSystemRecords::where('id', $id)->first();
        $flag = collect([
            'message' => $message,
            'id' => $campid,
        ]);
        broadcast(new CampaignSystemUpdate($flag));

        $c = CampaignUser::where('id', $campaignUserID)->get();
        foreach ($c as $c) {
            $c->update(['campaign_system_id' => $campaignSystemID, 'status_id' => 4]);
        }

        $message = CampaignUserRecords::where('id', $campaignUserID)->first();
        $flag = null;
        $flag = collect([
            'message' => $message,
            'id' => $campid,
        ]);
        broadcast(new CampaignUserUpdate($flag));

        //done waiting to finish rest before removing this //

        $flag = null;
        $flag = collect([
            'flag' => 3,
            'id' => $campid,
        ]);
        broadcast(new CampaignSystemUpdate($flag));
    }

    public function tableindex($campid)
    {
        $nodeJoin = [];
        $joins = NodeJoin::where('campaign_id', $campid)->get();
        if ($joins->count() > 0) {
            foreach ($joins as $join) {
                $pull = [
                    'id' => $join->id,
                    'campaign_system_id' => $join->campaign_system_id,
                    'campaign_user_id' => $join->campaign_user_id,
                    'charname' => $join->campaignUser->char_name,
                    'siteid' => $join->campaignUser->site_id,
                    'mainname' => User::where('id', $join->campaignUser->site_id)->value('name'),
                    'ship' => $join->campaignUser->ship,
                    'link' => intval($join->campaignUser->link),
                    'campaign_system_status_id' => intval($join->campaign_system_status_id),
                    'statusName' => CampaignSystemStatus::where('id', $join->campaign_system_status_id)->value('name'),
                    'campaign_sola_system_id' => CampaignSolaSystem::where('campaign_id', $join->campaignSystem->campaign_id)->where('system_id', $join->campaignSystem->system_id)->value('id'),
                    'campaign_id' => $campid,
                ];
                array_push($nodeJoin, $pull);
            }
        }

        return ['nodeJoin' => $nodeJoin];
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $campid)
    {
        $new = NodeJoin::create($request->all());
        $message = nodeJoinRecords($new->id);
        $flag = null;
        $flag = collect([
            'message' => $message,
            'id' => $campid,
        ]);
        broadcast(new NodeJoinNew($flag));

        $camp = CampaignSystem::where('id', $request['campaign_system_id'])->first();
        $count = $camp->node_join_count + 1;
        $camp->update(['node_join_count' => $count]);

        $message = CampaignSystemRecords::where('id', $request['campaign_system_id'])->first();
        $flag = null;
        $flag = collect([
            'message' => $message,
            'id' => $campid,
        ]);
        broadcast(new CampaignSystemUpdate($flag));
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

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id, $campid)
    {
        $n = NodeJoin::where('id', $id)->get();
        foreach ($n as $n) {
            $n->update($request->all());
        }

        $message = nodeJoinRecords($id);

        $flag = collect([
            'message' => $message,
            'id' => $campid,
        ]);
        broadcast(new NodeJoinUpdate($flag))->toOthers();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
