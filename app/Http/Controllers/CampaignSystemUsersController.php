<?php

namespace App\Http\Controllers;

use App\Events\CampaignUsersChanged;
use App\Models\Campaign;
use App\Models\CampaignSystemUsers;
use Illuminate\Http\Request;

class CampaignSystemUsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($campid)
    {
        $users = [];
        $members = CampaignSystemUsers::with('user')->where('campaign_id', $campid)->get();
        // dd($members);
        if ($members->count() > 0) {
            foreach ($members as $member) {
                $data = [];
                $data = [
                    'id' => $member->id,
                    'user_id' => $member->user_id,
                    'user_name' => $member->user->name,
                    'campaign_id' => $member->campaign_id,
                ];
                array_push($users, $data);
            }
        }
        // dd( ['users' => $users]);
        return ['users' => $users];
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $campid)
    {
        $data = null;
        if ($request['type'] == 1) {
            $campaign_id = Campaign::where('link', $campid)->value('id');
            $data = [
                'user_id' => $request['user_id'],
                'campaign_id' => $campaign_id,
            ];
        } else {
            $campaign_id = $campid;

            $data = [
                'user_id' => $request['user_id'],
                'campaign_id' => $request['campaign_id'],
            ];
        }
        CampaignSystemUsers::create($data);
        $flag = collect([
            'flag' => 5,
            'id' => $campaign_id,
        ]);
        broadcast(new CampaignUsersChanged($flag))->toOthers();
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
    public function destroy($id, $campid)
    {
        $c = CampaignSystemUsers::where('user_id', $id)->first();
        $c->delete();
        $flag = collect([
            'flag' => 5,
            'id' => $campid,
        ]);
        broadcast(new CampaignUsersChanged($flag))->toOthers();
    }
}
