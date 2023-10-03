<?php

namespace App\Console\Commands;

use App\Models\Alliance;
use App\Models\Auth;
use App\Models\Client;
use App\Models\Corp;
use App\Models\EveEsiStatus;
use App\Models\Userlogging;
use DateTime;
use GuzzleHttp\Client as GuzzleHttpClient;
use GuzzleHttp\Utils;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class UpdateStanding extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:standing';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $this->runStandingUpdate();
    }

    public function runStandingUpdate()
    {
        $check = EveEsiStatus::where('route', '/alliances/{alliance_id}/contacts/')->first();
        if ($check->status == 'green') {
            $this->checkKeys();
            $this->updateStanding();
        }
    }

    public function checkKeys()
    {
        Userlogging::create(['url' => 'demon Standing', 'user_id' => 9999999999]);
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
        $a = Alliance::whereNotNull('id')->get();
        foreach ($a as $a) {
            $a->update(['standing' => 0, 'color' => 0]);
        }
        $c = Corp::whereNotNull('id')->get();
        foreach ($c as $c) {
            $c->update(['standing' => 0, 'color' => 0]);
        }
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
        $this->info($standings);
        foreach ($standings as $standing) {
            $stand = collect($standing);

            // dd($stand->get('standing'));
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

        $a = Alliance::where('id', '1354830081')->get();
        foreach ($a as $a) {
            $a->update(['standing' => 10, 'color' => 3]);
        }
    }
}
