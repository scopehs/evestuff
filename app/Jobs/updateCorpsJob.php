<?php

namespace App\Jobs;

use App\Models\Corp;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;

class updateCorpsJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    protected $corpID;

    protected $allianceID;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($corpID, $allianceID)
    {
        $this->corpID = $corpID;
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
        $this->startCorpJob($this->corpID, $this->allianceID);
        activity()->enableLogging();
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
}
