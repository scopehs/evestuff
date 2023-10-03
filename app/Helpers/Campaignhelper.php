<?php

use App\Events\CampaignChanged;
use App\Events\CampaignSystemDelete;
use App\Events\CampaignSystemUpdate;
use App\Events\CampaignUpdate;
use App\Events\CampaignUserUpdate;
use App\Events\MultiCampaignUpdate;
use App\Events\NodeJoinDelete;
use App\Models\Campaign;
use App\Models\CampaignJoin;
use App\Models\CampaignRecords;
use App\Models\CampaignSolaSystem;
use App\Models\CampaignSystem;
use App\Models\CampaignSystemRecords;
use App\Models\CampaignSystemStatus;
use App\Models\CampaignSystemUsers;
use App\Models\CampaignUser;
use App\Models\CampaignUserRecords;
use App\Models\NodeJoin;
use App\Models\System;
use App\Models\User;
use GuzzleHttp\Client;
use GuzzleHttp\Utils;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;

if (!function_exists('nodeJoinRecords')) {
    function nodeJoinRecords($id)
    {
        $join = NodeJoin::where('id', $id)->first();
        $data = [

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
            'campaign_sola_system_id' => CampaignSolaSystem::where('campaign_id', $join->campaignSystem->campaign_id)->where('system_id', $join->campaignSystem->systemm_id)->value('id'),
            'campaign_id' => $join->campaign_id,

        ];

        return $data;
    }
}
if (!function_exists('runrun')) {
    function runrun()
    {
        Artisan::call('schedule:run');
    }
}
if (!function_exists('campaignUpdate')) {
    function campaignUpdate()
    {
        $checkflag = null;
        //Removing old Campaigns and all data from databae -- start////

        $toDelete = Campaign::where('status_id', 10)
            ->get();

        if ($toDelete->count() != 0) {
            foreach ($toDelete as $toDelete) {
                $c = CampaignUser::where('campaign_id', $toDelete->id)->get();
                foreach ($c as $c) {
                    $c->update(['system_id' => null, 'status_id' => 1]);
                }
                $c = CampaignSystemUsers::where('campaign_id', $toDelete->id)->get();
                foreach ($c as $c) {
                    $c->delete();
                }
                $c = CampaignSystem::where('campaign_id', $toDelete->id)->get();
                foreach ($c as $c) {
                    $c->delete();
                }
                $c = Campaign::where('id', $toDelete->id)->get();
                foreach ($c as $c) {
                    $c->delete();
                }
                $c = CampaignJoin::where('campaign_id', $toDelete->id)->get();
                foreach ($c as $c) {
                    $c->delete();
                }
                $c = CampaignSolaSystem::where('campaign_id', $toDelete->id)->get();
                foreach ($c as $c) {
                    $c->delete();
                }
                $flag = collect([
                    'flag' => 4,
                    'id' => $toDelete->id,
                ]);
                broadcast(new CampaignSystemUpdate($flag));
            }
        }

        //-----end----//

        //-----set all checks to 0 for start---///
        $c = Campaign::where('id', '>', 0)->get();
        foreach ($c as $c) {
            $c->update(['check' => 0]);
        }
        //-----end----//

        $flag = 0;
        $changed = collect([]);
        $client = new Client();
        $headers = [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'User-Agent' => 'evestuff.online evestuff@lol.com',
        ];
        $url = 'https://esi.evetech.net/latest/sovereignty/campaigns/?datasource=tranquility';
        $response = $client->request('GET', $url, [
            'headers' => $headers,
        ]);
        $response = Utils::jsonDecode($response->getBody(), true);

        foreach ($response as $var) {
            $event_type = $var['event_type'];
            if ($event_type == 'ihub_defense' || $event_type == 'tcu_defense') {
                if ($event_type == 'ihub_defense') {
                    $event_type = 32458;
                } else {
                    $event_type = 32226;
                }
                $id = $var['campaign_id'];

                $before = Campaign::where('id', $id)->get();
                $time = $var['start_time'];
                $start_time = fixtime($time);
                $data = [];
                $data = [
                    'attackers_score' => $var['attackers_score'],
                    'constellation_id' => $var['constellation_id'],
                    'alliance_id' => $var['defender_id'],
                    'defenders_score' => $var['defender_score'],
                    'event_type' => $event_type,
                    'system_id' => $var['solar_system_id'],
                    'start_time' => $start_time,
                    'structure_id' => $var['structure_id'],
                    'check' => 1,
                ];
                Campaign::updateOrCreate(['id' => $id], $data);
                // if (Campaign::where('id', $id)->where('link', "!=", null)->count() == 0) {
                //     $string = $id . $var['solar_system_id'] . $var['structure_id'] . substr(md5(rand()), 0, 7);
                //     Campaign::where('id', $id)->update(['link' => hash('ripemd128', $string)]);
                //     $checkflag = 1;
                // }

                if (Campaign::where('id', $id)->whereNotNull('link')->count() == 0) {
                    $string = Str::uuid();
                    $c = Campaign::where('id', $id)->get();
                    foreach ($c as $c) {
                        $c->update(['link' => $string]);
                    }
                    $checkflag = 1;
                }

                $after = Campaign::where('id', $id)->get();
                if ($before->count() > 0) {
                    $attackers_old = $before->toArray();
                    $attackers_old = floatval($attackers_old[0]['attackers_score']);
                    $defenders_old = $before->toArray();
                    $defenders_old = floatval($defenders_old[0]['defenders_score']);
                    $new = $var['attackers_score'];
                    if ($new != $attackers_old) {
                        // echo "diffurent";
                        $flag = 1;
                        $checkflag = 1;
                        $c = Campaign::where('id', $id)->get();
                        foreach ($c as $c) {
                            $c->update([
                                'defenders_score_old' => $defenders_old,
                                'attackers_score_old' => $attackers_old,
                            ]);
                        }
                        removeNode($id);
                    }
                } else {
                    $constellation = System::where('id', $var['solar_system_id'])->value('constellation_id');
                    $systems = System::where('constellation_id', $constellation)->select('id')->get();
                    foreach ($systems as $system) {
                        CampaignSolaSystem::create(['system_id' => $system['id'], 'campaign_id' => $id]);
                    }
                }
            }
        }
        // dd("fwefe");
        $now = now();
        $warmup = now()->subHours(1);
        $now10 = now()->subHours(12);
        $now2m = now()->subMinutes(2);
        $now10m = now()->subMinutes(10);
        $yesterday = now()->subHours(8);

        $check = Campaign::where('start_time', '<=', $now)->where('status_id', 1)->count();
        if ($check > 0) {
            $starting = Campaign::where('start_time', '<=', $now)->where('status_id', 1)->get();
            foreach ($starting as $start) {
                $start->update(['status_id' => 2, 'check' => 1]);
                $message = CampaignSystemRecords::where('id', $start->id)->first();
                $flag = null;
                $flag = collect([
                    'message' => $message,
                    'id' => $start->id,
                ]);
                broadcast(new CampaignSystemUpdate($flag))->toOthers();
            }

            $checkflag = 1;
        }

        $warmchecks = Campaign::where('warmup', 0)->where('status_id', 1)->get();
        foreach ($warmchecks as $warmcheck) {
            if (strtotime($warmcheck->start_time) - strtotime(now()) > 0 && strtotime($warmcheck->start_time) - strtotime(now()) < 3601) {
                $c = Campaign::where('id', $warmcheck['id'])->where('status_id', 1)->where('warmup', 0)->get();
                foreach ($c as $c) {
                    $c->update(['warmup' => 1]);
                }
                $message = CampaignRecords::where('id', $warmcheck['id'])->first();
                $flag = null;
                $flag = collect([
                    'message' => $message,
                    'id' => $check,
                ]);
                broadcast(new CampaignUpdate($flag))->toOthers();
                $checkflag = 1;
            }
        }

        $check = Campaign::where('check', 0)->count();

        if ($check > 0) {
            $warms = Campaign::where('end', '!=', null)->where('check', 0)->where('status_id', '<', 3)->get();
            foreach ($warms as $warm) {
                $warm->update(['status_id' => 3, 'warmup' => 0]);

                // removeNode($warm->id);
                $checkflag = 1;
                // echo "1";
            }

            $as = Campaign::where('end', null)
                ->where('check', 0)->get();
            foreach ($as as $a) {
                $a->update(['end' => $now, 'status_id' => 3]);
                $checkflag = 1;
                $message = CampaignRecords::where('id', $a->id)->first();
                $flag = null;
                $flag = collect([
                    'message' => $message,
                    'id' => $a->id,
                ]);
                broadcast(new CampaignUpdate($flag))->toOthers();

                $dCampaignSystems = CampaignSystem::where('campaign_id', $a->id)->get();
                foreach ($dCampaignSystems as $dCampaignSystem) {
                    $dNodes = NodeJoin::where('campaign_system_id', $dCampaignSystem->id)->get();
                    if ($dNodes->count() > 0) {
                        foreach ($dNodes as $dNode) {
                            $flag = collect([
                                'joinNodeID' => $dNode->id,
                                'id' => $dNode->campaign_id,
                            ]);
                            broadcast(new NodeJoinDelete($flag));

                            $dNode->delete();
                        }
                    }

                    $dCampaignUsers = CampaignUser::where('campaign_system_id', $dCampaignSystem->id)->get();
                    foreach ($dCampaignUsers as $dCampaignUser) {
                        $dCampaignUser->update(['campaign_system_id' => null, 'status_id' => 3]);
                        $message = CampaignUserRecords::where('id', $dCampaignUser->id)->first();
                        $flag = null;
                        $flag = collect([
                            'message' => $message,
                            'id' => $dCampaignUser->campaign_id,
                        ]);
                        broadcast(new CampaignUserUpdate($flag));
                    }

                    $flag = null;
                    $flag = collect([
                        'campSysID' => $dCampaignSystem->id,
                        'id' => $a->id,
                    ]);
                    broadcast(new CampaignSystemDelete($flag))->toOthers();
                    $dCampaignSystem->delete();
                }
            }

            $bs = Campaign::where('end', '<=', $now10m)
                ->where('check', 0)
                ->where('status_id', 3)->where('status_id', '!=', 4)->get();
            foreach ($bs as $b) {
                $b->update(['status_id' => 4]);
                $checkflag = 1;
                $message = CampaignRecords::where('id', $b->id)->first();
                $flag = null;
                $flag = collect([
                    'message' => $message,
                    'id' => $b->id,
                ]);
                broadcast(new CampaignUpdate($flag))->toOthers();
                // echo "3";
            }

            // ->update(['check' => 1]);
            $cs = Campaign::where('end', '<=', $now10)
                ->where('check', 0)
                ->where('status_id', 4)->get();
            foreach ($cs as $c) {
                $c->update(['status_id' => 10]);
                $checkflag = 1;
                $message = CampaignRecords::where('id', $c->id)->first();
                $flag = null;
                $flag = collect([
                    'message' => $message,
                    'id' => $c->id,
                ]);
                broadcast(new CampaignUpdate($flag))->toOthers();
                // echo "4";
            }
        }

        $finished = Campaign::where('status_id', 3)
            ->get();
        foreach ($finished as $finish) {
            $a = $finish->attackers_score;
            $d = $finish->defenders_score;

            if ($a > $d) {
                $c = Campaign::where('id', $finish->id)->get();
                foreach ($c as $c) {
                    $c->update(['attackers_score' => 1, 'defenders_score' => 0]);
                }
            } else {
                $c = Campaign::where('id', $finish->id)->get();
                foreach ($c as $c) {
                    $c->update(['attackers_score' => 0, 'defenders_score' => 1]);
                }
            }

            $campaignJoins = CampaignJoin::where('campaign_id', $finish->id)->get();
            foreach ($campaignJoins as $campaignJoin) {
                $campid = $campaignJoin->custom_campaign_id;
                $campaignJoin->delete();
                $flag = collect([
                    'flag' => 11,
                    'id' => $campid,
                ]);
                broadcast(new CampaignSystemUpdate($flag));
                broadcast(new MultiCampaignUpdate($flag));
            }

            $message = CampaignRecords::where('id', $finish->id)->first();
            $flag = null;
            $flag = collect([
                'message' => $message,
                'id' => $finish->id,
            ]);
            broadcast(new CampaignUpdate($flag))->toOthers();
            $checkflag = 1;
            // echo "5";
        }

        if ($checkflag == 1) {
            $flag = null;
            $message = 'hi';
            $flag = collect([
                'message' => $message,
                'id' => $check,
            ]);
            // echo "yoyo";
            broadcast(new CampaignChanged($flag))->toOthers();
        }

        //NEW SCRIPT FOR UPDATED CAMPAIGN/HACKING PAGE//
    }
}
if (!function_exists('removeNode')) {
    function removeNode($check)
    {
        $campaign = Campaign::find($check);
        $b_node_add = $campaign->campaignsystems()
            ->where('campaign_system_status_id', 4)
            ->count();
        $r_node_add = $campaign->campaignsystems()
            ->where('campaign_system_status_id', 5)
            ->count();

        $b_node_new = $campaign->b_node + $b_node_add;
        $r_node_new = $campaign->r_node + $r_node_add;

        $campaign->update(['b_node' => $b_node_new]);
        $campaign->update(['r_node' => $r_node_new]);

        $message = CampaignRecords::where('id', $check)->first();

        $campaign->campaignsystems()
            ->where('campaign_system_status_id', 4)
            ->update(['campaign_system_status_id' => 10]);

        $campaign->campaignsystems()
            ->where('campaign_system_status_id', 5)
            ->update(['campaign_system_status_id' => 10]);

        $user = $campaign->campaignsystems()
            ->where('campaign_system_status_id', 10)
            ->where('campaign_user_id', '!=', null)
            ->get();

        foreach ($user as $user) {
            $user->campaignusers()
                ->update([
                    'campaign_system_id' => null,
                    'status_id' => 3,
                ]);

            $message = CampaignUserRecords::where('id', $user->campaignusers()->value('id'))->first();
            $flag = null;
            $flag = collect([
                'message' => $message,
                'id' => $check,
            ]);
            broadcast(new CampaignUserUpdate($flag));
        }

        $dels = $campaign->campaignsystems()
            ->where('campaign_system_status_id', 10)->get();
        foreach ($dels as $del) {
            $flag = null;
            $flag = collect([
                'campSysID' => $del['id'],
                'id' => $check,
            ]);
            broadcast(new CampaignSystemDelete($flag));
        }

        $campaign->campaignsystems()
            ->where('campaign_system_status_id', 10)
            ->delete();

        $message = CampaignRecords::where('id', $check)->first();
        $flag = null;
        $flag = collect([
            'message' => $message,
            'id' => $check,
        ]);
        // echo "8";
        broadcast(new CampaignUpdate($flag));
    }
}
