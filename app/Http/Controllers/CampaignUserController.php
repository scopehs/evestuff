<?php

namespace App\Http\Controllers;

use App\Events\CampaignSystemUpdate;
use App\Events\CampaignUserDelete;
use App\Events\CampaignUserNew;
use App\Events\CampaignUserUpdate;
use App\Models\CampaignSystem;
use App\Models\CampaignSystemRecords;
use App\Models\CampaignUser;
use App\Models\CampaignUserRecords;
use Illuminate\Http\Request;

class CampaignUserController extends Controller
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

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $campid)
    {
        $new = CampaignUser::create($request->all());
        $userid = $new->id;
        $user = CampaignUserRecords::where('id', $userid)->first();
        $flag = null;
        $flag = collect([
            'message' => $user,
            'id' => $campid,
        ]);
        broadcast(new CampaignUserNew($flag))->toOthers();
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
        $c = CampaignUser::find($id)->get();
        foreach ($c as $c) {
            $c->update($request->all());
        }
        $message = CampaignUserRecords::where('id', $id)->first();
        $flag = collect([
            'message' => $message,
            'id' => $campid,
        ]);
        broadcast(new CampaignUserUpdate($flag));
    }

    public function updateadd(Request $request, $id, $campid)
    {
        $node = CampaignSystem::where('campaign_user_id', $id)->first();

        if ($node != null) {
            $node->update(['campaign_user_id' =>  null, 'campaign_system_status_id' => 1, 'end_time' => null]);
            $node->save();
            $message = CampaignSystemRecords::where('id', $node->id)->first();
            $flag = collect([
                'message' => $message,
                'id' => $campid,
            ]);
            broadcast(new CampaignSystemUpdate($flag))->toOthers();
        }

        $c = CampaignUser::find($id)->get();
        foreach ($c as $c) {
            $c->update($request->all());
        }
        $message = CampaignUserRecords::where('id', $id)->first();
        $flag = null;
        $flag = collect([
            'message' => $message,
            'id' => $campid,
        ]);
        broadcast(new CampaignUserNew($flag))->toOthers();
    }

    public function updateremove(Request $request, $id, $campid)
    {
        $node = CampaignSystem::where('campaign_user_id', $id)->first();

        if ($node != null) {
            $node->update(['campaign_user_id' =>  null, 'campaign_system_status_id' => 1, 'end_time' => null]);
            $node->save();
            $message = CampaignSystemRecords::where('id', $node->id)->first();
            $flag = collect([
                'message' => $message,
                'id' => $campid,
            ]);
            broadcast(new CampaignSystemUpdate($flag))->toOthers();
            $flag = null;
            $flag = collect([
                'flag' => 2,
                'id' => $campid,
            ]);
        }

        $c = CampaignUser::find($id)->get();
        foreach ($c as $c) {
            $c->update($request->all());
        }
        $user_id = CampaignUserRecords::where('id', $id)->value('id');
        $flag = collect([
            'userid' => $user_id,
            'id' => $campid,
        ]);
        broadcast(new CampaignUserDelete($flag))->toOthers();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, $campid, $siteid)
    {
        CampaignUser::destroy($id);
        $flag = collect([
            'userid' => $id,
            'id' => $campid,
        ]);

        broadcast(new CampaignUserDelete($flag))->toOthers();
    }
}
