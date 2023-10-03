<?php

namespace App\Http\Controllers;

use App\Models\CampaignSystemUsers;
use App\Models\CampaignUser;
use App\Models\StartCampaignJoins;
use App\Models\StartCampaigns;
use App\Models\StartCampaignSystems;
use App\Models\System;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class StartCampaignController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $list = [];
        $pull = StartCampaigns::all();
        foreach ($pull as $pull) {
            $data = [];
            $data = [
                'id' => $pull['id'],
                'name' => $pull['name'],
                'link' => $pull['link']
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
    public function store(Request $request, $name)
    {
        $data = $request->all();
        $link = Str::uuid();
        $user_id = Auth::id();

        $new = new StartCampaigns();
        $new->link = $link;
        $new->name = $name;
        $new->user_id = $user_id;
        $new->save();
        foreach ($data as $data) {
            $newJoin = new StartCampaignJoins();
            $newJoin->start_campaign_id = $new->id;
            $newJoin->constellation_id = $data['value'];
            $newJoin->save();
            $solas = System::where('constellation_id', $data['value'])->get();
            foreach ($solas as $sola) {
                $newSystem = new StartCampaignSystems();
                $newSystem->system_id = $sola->id;
                $newSystem->start_campaign_id = $new->id;
                $newSystem->constellation_id = $data['value'];
                $newSystem->save();
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $list = [];
        $pull = StartCampaigns::whereLink($id)->first();
        // foreach ($pull as $pull) {
        //     $data = [];
        //     $data = [
        //         'id' => $pull['id'],
        //         'name' => $pull['name'],
        //         'link' => $pull['link']
        //     ];
        //     array_push($list, $data);
        // }

        return ['campaign' => $pull];
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
        $s = StartCampaigns::where('id', $id)->get();
        foreach ($s as $s) {
            $s->delete();
        }
        $s = StartCampaignJoins::where('start_campaign_id', $id)->get();
        foreach ($s as $s) {
            $s->delete();
        }
        $s = StartCampaignSystems::where('start_campaign_id', $id)->get();
        foreach ($s as $s) {
            $s->delete();
        }
        $s = CampaignSystemUsers::where('campaign_id', $id)->get();
        foreach ($s as $s) {
            $s->delete();
        }
        $c = CampaignUser::where('campaign_id', $id)->get();
        foreach ($c as $c) {
            $c->update([
                'campaign_id' => null,
                'campaign_system_id' => null,
                'status_id' => 1,
            ]);
        }
    }
}
