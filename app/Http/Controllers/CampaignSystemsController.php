<?php

namespace App\Http\Controllers;

use App\Events\CampaignSolaSystemUpdate;
use App\Events\CampaignSystemDelete;
use App\Events\CampaignSystemNew;
use App\Events\CampaignSystemUpdate;
use App\Events\CampaignUserUpdate;
use App\Events\KickUserFromCampaign;
use App\Events\NodeAttackMessageUpdate;
use App\Events\NodeJoinDelete;
use App\Events\NodeMessageUpdate;
use App\Models\Campaign;
use App\Models\CampaignSolaSystem;
use App\Models\CampaignSystem;
use App\Models\CampaignSystemRecords;
use App\Models\CampaignSystemStatus;
use App\Models\CampaignSystemUsers;
use App\Models\CampaignUser;
use App\Models\CampaignUserRecords;
use App\Models\NodeJoin;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Traits\HasPermissions;
use Spatie\Permission\Traits\HasRoles;

class CampaignSystemsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    use HasPermissions;

    use HasRoles;

    public function load(Request $request)
    {
        $campid = null;
        if ($request['type'] == 1) {
            $campid = Campaign::where('link', $request['campaign_id'])->value('id');
            $userid = $request['user_id'];
        } else {
            $campid = $request['campaign_id'];
            $userid = $request['user_id'];
        }

        $dataSola = [];
        $pull = CampaignSolaSystem::where('campaign_id', $campid)->get();
        foreach ($pull as $pull) {
            $checker_name = null;
            $supervier_name = null;
            if ($pull['last_checked_user_id'] != null) {
                $checker_name = User::where('id', $pull['last_checked_user_id'])->value('name');
            }

            if ($pull['supervisor_id'] != null) {
                $supervier_name = User::where('id', $pull['supervisor_id'])->value('name');
            }

            $dataSola1 = [];
            $dataSola1 = [
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
            array_push($dataSola, $dataSola1);
        }
        $pull = [];
        $nodeJoin = [];
        $joins = NodeJoin::where('campaign_id', $campid)->get();
        if ($request['type'] == 1) {
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
        } else {
            if ($joins->count() > 0) {
                foreach ($joins as $join) {
                    // dd($join);
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
                        'campaign_sola_system_id' => CampaignSolaSystem::where('campaign_id', $campid)->where('system_id', $join->campaignSystem->system_id)->value('id'),
                        'campaign_id' => $campid,
                    ];
                    array_push($nodeJoin, $pull);
                }
            }
        }

        $check = Auth::user();
        if ($check->can('view_campaign_logs')) {
            $dataLog = [];
        } else {
            $dataLog = [];
        }

        return [
            'users' => CampaignUserRecords::where('campaign_id', $campid)->get(),
            'sola' => $dataSola,
            'nodejoin' => $nodeJoin,
            'systems' => CampaignSystemRecords::all(),
            'usersbyid' => CampaignUserRecords::where('site_id', $userid)->get(),
            'logs' => $dataLog,
        ];
    }

    public function index()
    {
        //
    }

    /**
     * Store a newlfwfwy created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $campid)
    {
        $system = CampaignSystem::create($request->all());
        $system->update(['input_time' => now()]);

        $message = CampaignSystemRecords::where('id', $system->id)->first();
        $flag = collect([
            'message' => $message,
            'id' => $campid,
        ]);
        broadcast(new CampaignSystemNew($flag));
        $flag = null;
        //done just waiting to remove//
        // $flag = collect([
        //     'flag' => 3,
        //     'id' => $campid,
        // ]);
        // broadcast(new CampaignSystemUpdate($flag))->toOthers();
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
        // dd($request->notes);

        $c = CampaignSystem::where('id', $id)->get();
        foreach ($c as $c) {
            $c->update($request->all());
        }

        $message = CampaignSystemRecords::where('id', $id)->first();
        $flag = collect([
            'message' => $message,
            'id' => $campid,
        ]);
        broadcast(new CampaignSystemUpdate($flag));
    }

    public function updateMessage(Request $request, $id)
    {
        // dd($request->notes);

        $c = CampaignSystem::where('id', $id)->get();
        foreach ($c as $c) {
            $c->update($request->all());
        }

        $message = CampaignSystemRecords::where('id', $id)->first();
        $flag = collect([
            'message' => $message,
            'id' => $id,
        ]);

        // dd($request, $id, $flag);
        broadcast(new NodeMessageUpdate($flag))->toOthers();
    }

    public function updateAttackMessage(Request $request, $id)
    {
        // dd($request->notes);

        $c = CampaignSystem::where('id', $id)->get();
        foreach ($c as $c) {
            $c->update($request->all());
        }

        $message = CampaignSystemRecords::where('id', $id)->first();
        if ($message->under_attack == 0) {
            $type = 2;
        } else {
            $type = 1;
        }
        $flag = collect([
            'type' => $type,
            'message' => $message,
            'id' => $id,
        ]);

        // dd($request, $id, $flag);
        broadcast(new NodeAttackMessageUpdate($flag))->toOthers();
    }

    public function removechar(Request $request, $campid)
    {
        $node = CampaignSystem::where('campaign_id', $request->campaign_id)
            ->where('system_id', $request->system_id)
            ->where('campaign_user_id', $request->campaign_user_id)->first();

        if ($node != null) {
            $node->update(['campaign_user_id' =>  null]);
            $node->save();
            $message = CampaignSystemRecords::where('id', $node->id)->first();
            $flag = collect([
                'message' => $message,
                'id' => $campid,
            ]);
            broadcast(new CampaignSystemUpdate($flag));
        }
    }

    public function movechar(Request $request, $campid)
    {
        $node = CampaignSystem::where('campaign_user_id', $request->id)->first();

        if ($node != null) {
            $node->update(['campaign_user_id' =>  null, 'campaign_system_status_id' => 1, 'end_time' => null]);
            $node->save();
            $message = CampaignSystemRecords::where('id', $node->id)->first();
            $flag = collect([
                'message' => $message,
                'id' => $campid,
            ]);
            broadcast(new CampaignSystemUpdate($flag))->toOthers();

            //waiting to be removed
            $flag = null;
            $flag = collect([
                'flag' => 2,
                'id' => $campid,
            ]);
            broadcast(new CampaignSystemUpdate($flag))->toOthers();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, $campid)
    {
        $users = CampaignUser::where('campaign_system_id', $id)->get();

        foreach ($users as $user) {
            $user->update(['campaign_system_id' => null, 'status_id' => 3]);
            $message = CampaignUserRecords::where('id', $user->id)->first();
            $flag = collect([
                'message' => $message,
                'id' => $campid,
            ]);
            broadcast(new CampaignUserUpdate($flag))->toOthers();
        }

        $node = NodeJoin::where('campaign_system_id', $id)->first();
        if ($node != null) {
            $flag = null;
            $flag = collect([
                'joinNodeID' => $node->id,
                'id' => $campid,
            ]);
            broadcast(new NodeJoinDelete($flag))->toOthers();
            $node->delete();
        }

        $flag = null;
        $flag = collect([
            'campSysID' => $id,
            'id' => $campid,
        ]);
        broadcast(new CampaignSystemDelete($flag))->toOthers();
        CampaignSystem::destroy($id);
    }

    public function checkAddChar($campid)
    {
        $flag = collect([
            'flag' => 5,
            'id' => $campid,
        ]);
        broadcast(new CampaignSystemUpdate($flag))->toOthers();
    }

    public function kickUser(Request $request, $campid)
    {
        $flag = collect([
            'id' => $campid,
            'user_id' => $request['user_id'],
        ]);
        broadcast(new KickUserFromCampaign($flag))->toOthers();
    }

    public function finishCampaign($campid)
    {
        $c = CampaignSystem::where('campaign_id', $campid)->get();
        foreach ($c as $c) {
            $c->delete();
        }
        $n = NodeJoin::where('campaign_id', $campid)->get();
        foreach ($n as $n) {
            $n->delete();
        }
        $c = CampaignSystemUsers::where('campaign_id', $campid)->get();
        foreach ($c as $c) {
            $c->delete();
        }
        $c = CampaignUser::where('campaign_id', $campid)->get();

        foreach ($c as $c) {
            $c->update([
                'campaign_id' => null,
                'campaign_system_id' => null,
                'status_id' => 1,
            ]);
        }
        $flag = collect([
            'flag' => 7,
            'id' => $campid,
        ]);
        broadcast(new CampaignSystemUpdate($flag))->toOthers();
    }

    public function mfinishCampaign($campid)
    {
        $c = CampaignSystem::where('custom_campaign_id', $campid)->get();
        foreach ($c as $c) {
            $c->delete();
        }
        $n = NodeJoin::where('campaign_id', $campid)->get();
        foreach ($n as $n) {
            $n->delete();
        }
        $c = CampaignSystemUsers::where('custom_campaign_id', $campid)->get();
        foreach ($c as $c) {
            $c->delete();
        }
        $c = CampaignUser::where('campaign_id', $campid)->get();

        foreach ($c as $c) {
            $c->update([
                'campaign_id' => null,
                'campaign_system_id' => null,
                'status_id' => 1,
            ]);
        }
        $flag = collect([
            'flag' => 7,
            'id' => $campid,
        ]);
        broadcast(new CampaignSystemUpdate($flag))->toOthers();
    }

    public function tidi(Request $request, $sysid, $campid)
    {
        $systems = CampaignSystem::where('system_id', $sysid)
            ->where('campaign_id', $campid)
            ->where('end_time', '!=', null)
            ->where('end_time', '>', now())
            ->where('campaign_system_status_id', '!=', 4)
            ->where('campaign_system_status_id', '!=', 5)
            ->where('campaign_system_status_id', '!=', 10)
            ->get();

        // dd($systems->count());
        if ($systems->count() != 0) {
            foreach ($systems as $system) {
                $time_passed = strtotime(now()) - strtotime($system->input_time);
                $base_time = $system->base_time - $time_passed;
                $time_left = $base_time / ($request->newTidi / 100);
                if ($time_left <= 0) {
                    $time_left = $time_left * -1;
                }
                $end_time = now()->modify('+ '.round($time_left).' seconds');
                $system->update(['end_time' => $end_time, 'input_time' => now(), 'base_time' => $base_time]);
                $system->save();
                $message = CampaignSystemRecords::where('id', $system->id)->first();
                $flag = collect([
                    'message' => $message,
                    'id' => $campid,
                ]);
                broadcast(new CampaignSystemUpdate($flag));
            }
        }
        $c = CampaignSolaSystem::where('id', $request->solaID)->get();
        foreach ($c as $c) {
            $c->update(['tidi' => $request->newTidi]);
        }
        $pull = CampaignSolaSystem::where('id', $request->solaID)->first();
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
        broadcast(new CampaignSolaSystemUpdate($flag));
    }

    public function tidimulti(Request $request, $sysid, $campid)
    {
        $systems = CampaignSystem::where('system_id', $sysid)
            ->where('custom_campaign_id', $campid)
            ->where('end_time', '!=', null)
            ->where('end_time', '>', now())
            ->where('campaign_system_status_id', '!=', 4)
            ->where('campaign_system_status_id', '!=', 5)
            ->where('campaign_system_status_id', '!=', 10)
            ->get();

        // dd($systems->count());
        if ($systems->count() != 0) {
            foreach ($systems as $system) {
                $time_passed = strtotime(now()) - strtotime($system->input_time);
                $base_time = $system->base_time - $time_passed;
                $time_left = $base_time / ($request->newTidi / 100);
                $end_time = now()->modify('+ '.round($time_left).' seconds');
                $system->update(['end_time' => $end_time, 'input_time' => now(), 'base_time' => $base_time]);
                $system->save();
                $message = CampaignSystemRecords::where('id', $system->id)->first();
                $flag = collect([
                    'message' => $message,
                    'id' => $campid,
                ]);
                broadcast(new CampaignSystemUpdate($flag));
            }
        }
        $c = CampaignSolaSystem::where('id', $request->solaID)->get();
        foreach ($c as $c) {
            $c->update(['tidi' => $request->newTidi]);
        }
        $pull = CampaignSolaSystem::where('id', $request->solaID)->first();
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
        broadcast(new CampaignSolaSystemUpdate($flag));
    }
}
