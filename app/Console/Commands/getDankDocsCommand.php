<?php

namespace App\Console\Commands;

use App\Models\OperationInfoDoctrine;
use App\Models\Userlogging;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class getDankDocsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'get:dankdocs';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Gets the doctrine list from Dank';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $variables = json_decode(base64_decode(getenv('PLATFORM_VARIABLES')), true);
        Userlogging::create(['url' => 'demon GetDank', 'user_id' => 9999999999]);
        $token = env('DANK_TOKEN', ($variables && array_key_exists('DANK_TOKEN', $variables)) ? $variables['DANK_TOKEN'] : 'null');
        $response = Http::withHeaders([
            'X-ApiKey' => $token,
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'User-Agent' => 'evestuff.online evestuff@lol.com',
        ])->get('https://fleets.apps.gnf.lt/api/v1/coordination/fleet-setups');

        $datas =  $response->json();
        OperationInfoDoctrine::where('id', '<', 10000)->delete();
        foreach ($datas as $data) {
            $new = new OperationInfoDoctrine();
            $new->id = $data['id'];
            $new->name = $data['name'];
            $new->save();
        }

        operationDoctrineBcast(12);
    }
}
