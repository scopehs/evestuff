<?php

namespace App\Console\Commands;

use App\Jobs\updateAlliancesJob;
use App\Models\Alliance;
use App\Models\Auth;
use App\Models\Client;
use App\Models\Corp;
use App\Models\Userlogging;
use COM;
use DateTime;
use GuzzleHttp\Client as GuzzleHttpClient;
use GuzzleHttp\Utils;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class UpdateAlliances extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:alliances';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This will update alliances and standing';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        // $status = checkeve();
        // if ($status == 1) {
        //     AllianceupdateAlliances();
        // }
        //############doing it in job###################
        Userlogging::create(['url' => 'demon Alliance', 'user_id' => 9999999999]);
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'User-Agent' => 'evestuff.online evestuff@lol.com',
        ])->get('https://esi.evetech.net/latest/alliances/?datasource=tranquility');
        $allianceIDs = $response->collect();
        $deads = Alliance::whereNotIn('id', $allianceIDs)->get();
        $deadIDs = $deads->pluck('id');
        $c = Corp::whereIn('alliance_id', $deadIDs)->get();
        foreach ($c as $c) {
            $c->update(['alliance_id' => 0]);
        }
        $a = Alliance::whereNotIn('id', $allianceIDs)->get();
        foreach ($a as $a) {
            $a->delete();
        }

        foreach ($allianceIDs as $allianceID) {
            updateAlliancesJob::dispatch($allianceID)->onQueue('alliance');
        }
        // UpdateStandingJob::dispatch()->onQueue('allince')->delay(now()->addMinutes(10));
        //############################
        //## here is for not doing it in a job #######

        // Alliance::where('id', '>', 0)->update(['active' => 0]);
        // $response =  Http::withHeaders([
        //     'Content-Type' => 'application/json',
        //     "Accept" => "application/json",
        //     'User-Agent' => 'evestuff.online evestuff@lol.com'
        // ])->get("https://esi.evetech.net/latest/alliances/?datasource=tranquility");
        // $allianceIDs = $response->collect();

        // foreach ($allianceIDs as $allianceID) {
        //     $this->startAlliance($allianceID);
        // }

        // $this->runStandingUpdate();
        // Corp::where('alliance_id', 0)->delete();

        //###################
    }

    public function startAlliance($allianceID)
    {
        $done = 0;
        $corpCount = 0;

        do {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'User-Agent' => 'evestuff.online evestuff@lol.com',
            ])->get('https://esi.evetech.net/latest/alliances/' . $allianceID . '/?datasource=tranquility');

            if ($response->successful()) {
                $done = 3;
                $allianceInfo = $response->collect();
                $this->info($allianceInfo);
                Alliance::updateOrCreate(
                    ['id' => $allianceID],
                    [
                        'name' => $allianceInfo->get('name'),
                        'ticker' => $allianceInfo->get('ticker'),
                        'standing' => 0,
                        'active' => 1,
                        'url' => 'https://images.evetech.net/Alliance/' . $allianceID . '_64.png',
                        'color' => 0,
                    ]
                );
                $c = Corp::where('alliance_id', $allianceID)->get();
                foreach ($c as $c) {
                    $c->update(['alliance_id' => null]);
                }
                do {
                    $responseCorp = Http::withHeaders([
                        'Content-Type' => 'application/json',
                        'Accept' => 'application/json',
                        'User-Agent' => 'evestuff.online evestuff@lol.com',
                    ])->get('https://esi.evetech.net/latest/alliances/' . $allianceID . '/corporations/?datasource=tranquility');
                    if ($responseCorp->successful()) {
                        $corpCount = 3;
                        $corpIDs = $responseCorp->collect();
                        $this->info($corpIDs);
                        foreach ($corpIDs as $corpID) {
                            $corpCheck = Corp::where('id', $corpID)->first();
                            if ($corpCheck) {
                                $corpCheck->update(['alliance_id' => $allianceID]);
                            } else {
                                $this->startCorpJob($corpID, $allianceID);
                            }
                        }
                    } else {
                        $headers = $response->headers();
                        $sleep = $headers['X-Esi-Error-Limit-Reset'][0];
                        sleep($sleep);
                        $corpCount++;
                    }
                } while ($corpCount != 3);
            } else {
                $headers = $response->headers();
                $sleep = $headers['X-Esi-Error-Limit-Reset'][0];
                sleep($sleep);
                $done++;
            }
        } while ($done != 3);
    }

    public function startCorpJob($corpID, $allianceID)
    {
        $corpPull = 0;
        do {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'User-Agent' => 'evestuff.online evestuff@lol.com',
            ])->get('https://esi.evetech.net/latest/corporations/' . $corpID . '/?datasource=tranquility');
            if ($response->successful()) {
                $corpPull = 3;
                $corpInfo = $response->collect();
                $this->info($corpInfo);
                Corp::updateOrCreate(
                    ['id' => $corpID],
                    [
                        'alliance_id' => $allianceID,
                        'name' => $corpInfo->get('name'),
                        'ticker' => $corpInfo->get('ticker'),
                        'color' => 0,
                        'standing' => 0,
                        'active' => 1,
                        'url' => 'https://images.evetech.net/Corporation/' . $corpID . '_64.png',

                    ]
                );
            } else {
                $headers = $response->headers();
                $sleep = $headers['X-Esi-Error-Limit-Reset'][0];
                sleep($sleep);
                $corpPull++;
            }
        } while ($corpPull != 3);
    }

    public function runStandingUpdate()
    {
        $this->checkKeys();
        $this->updateStanding();
    }

    public function checkKeys()
    {
        $auths = Auth::all();
        foreach ($auths as $auth) {
            // print " - " . $auth->name . " - ";

            $expire_date = new DateTime($auth->expire_date);
            $date = new DateTime();

            if ($date > $expire_date) {
                // if (1 > 0) {
                // print "old!¬¬¬¬¬ !!!! ----";
                $client = Client::first();
                $http = new GuzzleHttpCLient();

                $headers = [
                    'Authorization' => 'Basic ' . $client->code,
                    'Content-Type' => 'application/x-www-form-urlencoded',
                    'Host' => 'login.eveonline.com',
                    'User-Agent' => 'evestuff.online evestuff@lol.com',

                ];
                $body = 'grant_type=refresh_token&refresh_token=' . $auth->refresh_token;
                // print $body;
                $response = $http->request('POST', 'https://login.eveonline.com/v2/oauth/token', [
                    'headers' => $headers,
                    'body' => $body,
                ]);
                $data = Utils::jsonDecode($response->getBody(), true);
                // dd($data);
                $date = new DateTime();
                $date = $date->modify('+19 minutes');
                $auth->update(['access_token' => $data['access_token'], 'refresh_token' => $data['refresh_token'], 'expire_date' => $date]);
            }
        }
    }

    public function updateStanding()
    {
        $token = Auth::where('flag_standing', 0)->first();
        if ($token == null) {
            $a = Auth::where('flag_standing', 1)->get();
            foreach ($a as $a) {
                $a->update(['flag_standing' => 0]);
            }
            $token = Auth::where('flag_standing', 0)->first();
            $token->update(['flag_note' => 1]);
            $url = 'https://esi.evetech.net/latest/alliances/1354830081/contacts/?datasource=tranquility';
        } else {
            $token->update(['flag_standing' => 1]);
            $url = 'https://esi.evetech.net/latest/alliances/1354830081/contacts/?datasource=tranquility';
        }

        $response = Http::withToken($token->access_token)->withHeaders([
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'User-Agent' => 'evestuff.online evestuff@lol.com',
        ])->get($url);

        $standings = $response->collect();
        foreach ($standings as $standing) {
            $stand = collect($standing);
            if ($stand->get('standing') > 0) {
                $color = 2;
            } else {
                $color = 1;
            }

            if ($stand->get('contact_type') == 'alliance') {
                $a = Alliance::where('id', $stand->get('contact_id'))->get();
                foreach ($a as $a) {
                    $a->update([
                        'color' => $color,
                        'standing' => $stand->get('standing'),
                    ]);
                }
            }

            if ($stand->get('contact_type') == 'corporation') {
                $c = Corp::where('id', $stand->get('contact_id'))->get();
                foreach ($c as $c) {
                    $c->update([
                        'color' => $color,
                        'standing' => $stand->get('standing'),
                    ]);
                }
            }
        }
        $a = Alliance::where('color', '0')->get();
        foreach ($a as $a) {
            $a->update(['color' => 1]);
        }
        $a = Alliance::whereNull('standing')->get();
        foreach ($a as $a) {
            $a->update(['standing' => 0]);
        }
        $a = Alliance::where('id', '1354830081')->get();
        foreach ($a as $a) {
            $a->update(['standing' => 10, 'color' => 3]);
        }
        $c = Corp::whereNull('standing')->get();
        foreach ($c as $c) {
            $c->update(['standing' => 0]);
        }
        $c = Corp::where('color', '0')->get();
        foreach ($c as $c) {
            $c->update(['color' => 1]);
        }
    }
}
