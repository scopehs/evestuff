<?php

namespace App\Jobs;

use App\Models\WebWay;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;

class updateWebwayJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    protected $end_system_id;

    protected $start_system_id;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($start_system_id, $end_system_id)
    {
        $this->end_system_id = $end_system_id;
        $this->start_system_id = $start_system_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        activity()->disableLogging();
        $this->updateWebway($this->start_system_id, $this->end_system_id);
        activity()->enableLogging();
    }

    public function updateWebway($start_system_id, $end_system_id)
    {
        $variables = json_decode(base64_decode(getenv('PLATFORM_VARIABLES')), true);
        /*
        send = [
            startSystem => start system get from env (1dq)
            endStstem => $end_system_id
        ]
       return =  api code to request too webway repos $response will be:
            [
                link: UUID of the saved route
                jumps: number of jumps from 1dq ( ID got from env file) to target system
                link_p: UUID of the saved route (with permissions)
                jumps_p: number of jumps from 1dq ( ID got from env file) to target system (with permissions)
            ]
        */

        $webwayURL = env('WEBWAY_URL', ($variables && array_key_exists('WEBWAY_URL', $variables)) ? $variables['WEBWAY_URL'] : null);
        $webwayToken = env('WEBWAY_TOKEN', ($variables && array_key_exists('WEBWAY_TOKEN', $variables)) ? $variables['WEBWAY_TOKEN'] : null);

        $data = [
            'startSystem' => $start_system_id,
            'endSystem' => $end_system_id,
        ];

        Http::withToken($webwayToken)
            ->withHeaders([
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'User-Agent' => 'evestuff.online evestuff@lol.com',
            ])->post($webwayURL, $data);
    }
}
