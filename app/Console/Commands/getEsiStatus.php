<?php

namespace App\Console\Commands;

use App\Models\EveEsiStatus;
use App\Models\Userlogging;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class getEsiStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:EveEsiStatus';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'gets all the info from https://esi.evetech.net/status.json?version=latest';

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

        Userlogging::create(['url' => 'demon EVEEsiStatus', 'user_id' => 9999999999]);
        EveEsiStatus::truncate();
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'User-Agent' => 'evestuff.online evestuff@lol.com',
        ])->get('https://esi.evetech.net/status.json?version=latest');
        $status = $response->collect();
        foreach ($status as $status) {
            $endpoint = $status['endpoint'];
            $method = $status['method'];
            $stat = $status['status'];
            $route = $status['route'];
            $tag = $status['tags'][0];

            EveEsiStatus::updateOrCreate(
                ['route' => $route, 'method' => $method],
                ['status' => $stat, 'tags' => $tag, 'endpoint' => $endpoint]
            );
        }
    }
}
