<?php

namespace App\Http\Controllers;

use App\Events\CampaignSystemDelete;
use App\Events\CampaignSystemUpdate;
use App\Events\CampaignUserUpdate;
use App\Events\NodeJoinDelete;
use App\Models\CampaignJoin;
use App\Models\CampaignSolaSystem;
use App\Models\CampaignSystem;
use App\Models\CampaignSystemUsers;
use App\Models\CampaignUser;
use App\Models\CampaignUserRecords;
use App\Models\CustomCampaign;
use App\Models\NodeJoin;
use Illuminate\Http\Request;

class CustomCampaignsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $list = [];
        $pull = CustomCampaign::where('status_id', '<', 3)->with('status')->get();
        foreach ($pull as $pull) {
            $data = [];
            $data = [
                'id' => $pull['id'],
                'name' => $pull['name'],
                'status_id' => $pull['status_id'],
                'status_name' => $pull['status']['name'],
            ];
            array_push($list, $data);
        }

        return ['campaigns' => $list];
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $campid, $name)
    {
        $data = $request->all();
        // dd($data);
        CustomCampaign::create(['id' => $campid, 'name' => $name]);
        foreach ($data as $data) {
            // dd($data);
            CampaignJoin::create(['custom_campaign_id' => $campid, 'campaign_id' => $data]);
            $solas = CampaignSolaSystem::where('campaign_id', $data)->get();
            foreach ($solas as $sola) {
                if (CampaignSolaSystem::where('campaign_id', $campid)->where('system_id', $sola['system_id'])->count() < 1) {
                    CampaignSolaSystem::create(['system_id' => $sola['system_id'], 'campaign_id' => $campid]);
                }
            }
        }
    }

    public function edit(Request $request, $campid, $name)
    {
        $data = $request->all();
        foreach ($data as $data) {
            $c = CampaignJoin::where('custom_campaign_id', $campid)->where('campaign_id', $data)->get();
            foreach ($c as $c) {
                $c->delete();
            }
        }
        $oldCampaignIDs = CampaignJoin::where('custom_campaign_id', $campid)->get();

        foreach ($oldCampaignIDs as $oldCampaignID) {
            $systemNodes = CampaignSystem::where('campaign_id', $oldCampaignID->campaign_id)->where('custom_campaign_id', $campid)->get();
            foreach ($systemNodes as $systemNode) {
                $flag = null;
                $flag = collect([
                    'campSysID' => $systemNode->id,
                    'id' => $campid,
                ]);
                broadcast(new CampaignSystemDelete($flag))->toOthers();

                if ($systemNode->node_join_count > 0) {
                    $nodes = NodeJoin::where('campaign_system_id', $systemNode->id);

                    foreach ($nodes as $node) {
                        $flag = null;
                        $flag = collect([
                            'joinNodeID' => $node->id,
                            'id' => $campid,
                        ]);
                        broadcast(new NodeJoinDelete($flag))->toOthers();
                        $node->delete();
                    }
                }
                $users = CampaignUser::where('campaign_system_id', $systemNode->id)->get();
                foreach ($users as $user) {
                    $user->update(['campaign_system_id' => null, 'status_id' => 3]);
                    $message = CampaignUserRecords::where('id', $user->id)->first();
                    $flag = collect([
                        'message' => $message,
                        'id' => $campid,
                    ]);
                    broadcast(new CampaignUserUpdate($flag));
                }
            }
            $oldCampaignID->delete();
        }

        $data = $request->all();
        $c = CampaignJoin::where('custom_campaign_id', $campid)->get();
        foreach ($c as $c) {
            $c->delete();
        }
        $c = CampaignSolaSystem::where('campaign_id', $campid)->get();
        foreach ($c as $c) {
            $c->delete();
        }
        $c = CustomCampaign::find($campid)->get();
        foreach ($c as $c) {
            $c->update(['name' => $name]);
        }

        foreach ($data as $data) {
            // dd($data);
            CampaignJoin::create(['custom_campaign_id' => $campid, 'campaign_id' => $data]);
            $solas = CampaignSolaSystem::where('campaign_id', $data)->get();
            foreach ($solas as $sola) {
                if (CampaignSolaSystem::where('campaign_id', $campid)->where('system_id', $sola['system_id'])->count() < 1) {
                    CampaignSolaSystem::create(['system_id' => $sola['system_id'], 'campaign_id' => $campid]);
                }
            }
        }

        $flag = collect([
            'flag' => 11,
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
        $c = CustomCampaign::where('id', $id)->get();
        foreach ($c as $c) {
            $c->delete();
        }
        $c = CampaignJoin::where('custom_campaign_id', $id)->get();
        foreach ($c as $c) {
            $c->delete();
        }
        $c = CampaignSystem::where('custom_campaign_id', $id)->get();
        foreach ($c as $c) {
            $c->delete();
        }
        $c = NodeJoin::where('campaign_id', $id)->get();
        foreach ($c as $c) {
            $c->delete();
        }
        $c = CampaignSystemUsers::where('custom_campaign_id', $id)->get();
        foreach ($c as $c) {
            $c->delete();
        }
        $c = CampaignUser::where('campaign_id', $id)->get();
        foreach ($c as $c) {
            $c->update([
                'campaign_id' => null,
                'campaign_system_id' => null,
                'status_id' => 1,
            ]);
        }
        $c = CampaignSolaSystem::where('campaign_id', $id)->get();
        foreach ($c as $c) {
            $c->delete();
        }
    }
}
