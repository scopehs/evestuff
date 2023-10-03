<?php

namespace App\Jobs;

use App\Events\dScanSoloUpdate;
use App\Models\Alliance;
use App\Models\Character;
use App\Models\Corp;
use App\Models\DscanLocal;
use Http;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class getLocalNamesJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;


    protected $charID;
    /**
     * Create a new job instance.
     */
    public function __construct($charID)
    {
        $this->charID = $charID;
    }




    /**
     * Execute the job.
     */
    public function handle(): void
    {
        activity()->disableLogging();
        $char = Character::whereId($this->charID)->whereNull('corp_id')->first();
        if ($char) {
            $this->addCorpID($this->charID);
        }


        $dscans = DscanLocal::where('character_id', $this->charID)->get();
        foreach ($dscans as $dscan) {
            $message = getDscanLocalInfo($dscan->dscan->link, $this->charID);
            $flag = collect([
                'id' => $dscan->dscan->link,
                'flag' => 2,
                'message' => $message,
            ]);
            broadcast(new dScanSoloUpdate($flag));
        }


        activity()->enableLogging();
    }


    public function addCorpID($charID)
    {
        $char = Character::whereId($charID)->first();
        $done = 0;
        do {
            $charRes = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'User-Agent' => 'evestuff.online evestuff@lol.com',
            ])->get('https://esi.evetech.net/latest/characters/' . $charID . '/?datasource=tranquility');
            if ($charRes->successful()) {

                $resChar = $charRes->json();

                $char->corp_id = $resChar['corporation_id'];
                $char->save();
                $this->addCorp($resChar['corporation_id']);
                $done = 3;
            } else {

                $headers = $charRes->headers();
                $sleep = $headers['X-Esi-Error-Limit-Reset'][0];
                sleep($sleep);
                $done++;
            }
        } while ($done != 3);
    }


    public function addCorp($corpID)
    {
        $corp =  Corp::whereId($corpID)->first();
        if (!$corp) {
            $corpPull = 0;
            do {
                $corpRes = Http::withHeaders([
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                    'User-Agent' => 'evestuff.online evestuff@lol.com',
                ])->get('https://esi.evetech.net/latest/corporations/' . $corpID . '/?datasource=tranquility');
                if ($corpRes->successful()) {
                    $corpPull = 3;
                    $corpInfo = $corpRes->collect();
                    Corp::updateOrCreate(
                        ['id' => $corpID],
                        [
                            'alliance_id' => $corpInfo->get('alliance_id') ?? null,
                            'name' => $corpInfo->get('name'),
                            'ticker' => $corpInfo->get('ticker'),
                            'active' => 1,
                            'url' => 'https://images.evetech.net/Corporation/' . $corpID . '_64.png',

                        ]
                    );
                    $this->addAlliance($corpInfo->get('alliance_id'));
                } else {
                    $headers = $corpRes->headers();
                    $sleep = $headers['X-Esi-Error-Limit-Reset'][0];
                    sleep($sleep);
                    $corpPull++;
                }
            } while ($corpPull != 3);
        }
    }

    public function addAlliance($allianceID)
    {
        $alliance =  Alliance::whereId($allianceID)->first();
        if (!$alliance) {
            $alliancePull = 0;
            do {
                $allianceRes = Http::withHeaders([
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                    'User-Agent' => 'evestuff.online evestuff@lol.com',
                ])->get('https://esi.evetech.net/latest/alliances/' . $allianceID . '/?datasource=tranquility');
                if ($allianceRes->successful()) {
                    $alliancePull = 3;
                    $allianceInfo = $allianceRes->collect();
                    Alliance::updateOrCreate(
                        ['id' => $allianceID],
                        [
                            'name' => $allianceInfo->get('name'),
                            'ticker' => $allianceInfo->get('ticker'),
                            'active' => 1,
                            'url' => 'https://images.evetech.net/Alliance/' . $allianceID . '_64.png',
                        ]
                    );
                } else {
                    $headers = $allianceRes->headers();
                    $sleep = $headers['X-Esi-Error-Limit-Reset'][0];
                    sleep($sleep);
                    $alliancePull++;
                }
            } while ($alliancePull != 3);
        }
    }
}
