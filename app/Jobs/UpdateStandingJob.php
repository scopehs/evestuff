<?php

namespace App\Jobs;

use App\Models\Alliance;
use App\Models\Auth;
use App\Models\Client;
use App\Models\Corp;
use DateTime;
use GuzzleHttp\Client as GuzzleHttpClient;
use GuzzleHttp\Utils;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;

class UpdateStandingJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        activity()->disableLogging();
        $this->runStandingUpdate();
        activity()->enableLogging();
    }

    public function runStandingUpdate()
    {
        $this->checkKeys();
        $this->updateStanding();
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

    public function checkKeys()
    {
        $auths = Auth::all();
        foreach ($auths as $auth) {
            $expire_date = new DateTime($auth->expire_date);
            $date = new DateTime();

            if ($date > $expire_date) {
                $client = Client::first();
                $http = new GuzzleHttpCLient();

                $headers = [
                    'Authorization' => 'Basic ' . $client->code,
                    'Content-Type' => 'application/x-www-form-urlencoded',
                    'Host' => 'login.eveonline.com',
                    'User-Agent' => 'evestuff.online evestuff@lol.com',

                ];
                $body = 'grant_type=refresh_token&refresh_token=' . $auth->refresh_token;
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
}
