<?php

namespace App\Http\Controllers;

use App\Events\CampaignSolaSystemUpdate;
use App\Events\CampaignSystemUpdate;
use App\Models\CampaignSolaSystem;
use App\Models\User;
use Illuminate\Http\Request;

class CampaignSolaSystemsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = [];
        $pull = CampaignSolaSystem::all();
        foreach ($pull as $pull) {
            $checker_name = User::where('id', $pull['last_checked_user_id'])->value('name');
            $supervier_name = User::where('id', $pull['supervisor_id'])->value('name');

            $data1 = [];
            $data1 = [
                'id' => $pull['id'],
                'system_id' => $pull['system_id'],
                'campaign_id' => $pull['campaign_id'],
                'supervisor_id' => $pull['supervisor_id'],
                'supervier_user_name' => $supervier_name,
                'last_checked_user_id' => $pull['last_checked_user_id'],
                'last_checked_user_name' => $checker_name,
                'last_checked' => $pull['last_checked'],
                'tidi' => $pull['tidi'],
            ];
            array_push($data, $data1);
        }

        return ['data' => $data];
    }

    // dd($data);

    // return ['data' =>]

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

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $solaid, $campid)
    {
        //CampaignSystemRecords::find($id)->update($request->all());
        $c = CampaignSolaSystem::find($solaid)->get();
        foreach ($c as $c) {
            $c->update($request->all());
        }
        $pull = CampaignSolaSystem::where('id', $solaid)->first();
        $checker_name = User::where('id', $pull['last_checked_user_id'])->value('name');
        $supervier_name = User::where('id', $pull['supervisor_id'])->value('name');
        $message = [
            'id' => $pull['id'],
            'system_id' => $pull['system_id'],
            'campaign_id' => $pull['campaign_id'],
            'supervisor_id' => $pull['supervisor_id'],
            'supervier_user_name' => $supervier_name,
            'last_checked_user_id' => $pull['last_checked_user_id'],
            'last_checked_user_name' => $checker_name,
            'last_checked' => $pull['last_checked'],
            'tidi' => $pull['tidi'],
        ];

        $flag = collect([
            'message' => $message,
            'id' => $campid,
        ]);
        broadcast(new CampaignSolaSystemUpdate($flag))->toOthers();

        // $flag = null;
        // $flag = collect([
        //     'flag' => 8,
        //     'id' => $campid,
        // ]);
        // broadcast(new CampaignSystemUpdate($flag))->toOthers();
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
