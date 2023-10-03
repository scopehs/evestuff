<?php

namespace App\Jobs;

use App\Models\Alliance;
use App\Models\Corp;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;

class updateAlliancesJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    protected $allianceID;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($allianceID)
    {
        $this->allianceID = $allianceID;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        activity()->disableLogging();
        $this->startAlliance($this->allianceID);
        activity()->enableLogging();
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
                Alliance::updateOrCreate(
                    ['id' => $allianceID],
                    [
                        'name' => $allianceInfo->get('name'),
                        'ticker' => $allianceInfo->get('ticker'),
                        'active' => 1,
                        'url' => 'https://images.evetech.net/Alliance/' . $allianceID . '_64.png',
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
                Corp::updateOrCreate(
                    ['id' => $corpID],
                    [
                        'alliance_id' => $allianceID,
                        'name' => $corpInfo->get('name'),
                        'ticker' => $corpInfo->get('ticker'),
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
}
