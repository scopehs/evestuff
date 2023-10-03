<?php

namespace App\Jobs;

use App\Models\Group;
use App\Models\Item;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;

class updateGroupJob implements ShouldQueue
{
    use Dispatchable;use InteractsWithQueue;use Queueable;use SerializesModels;
    protected $groupID;
    /**
     * Create a new job instance.
     */
    public function __construct($groupID)
    {
        $this->groupID = $groupID;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $groupID = $this->groupID;
        $group = Group::where('id', $groupID)->first();
        $groupPull = 0;
        do {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'User-Agent' => 'evestuff.online evestuff@lol.com',
            ])->get('https://esi.evetech.net/latest/universe/groups/' . $groupID . '/?datasource=tranquility&language=en');

            if ($response->successful()) {
                $groupPull = 3;
                $res = $response->json();
                $name = $res['name'];
                $group->name = $name;
                $group->save();
                $types = $res['types'];

                foreach ($types as $type) {
                    Item::updateOrCreate(
                        ['id' => $type],
                        ['group_id' => $groupID]
                    );
                }
            } else {

                $headers = $response->headers();
                $sleep = $headers['X-Esi-Error-Limit-Reset'][0];
                sleep($sleep);
                $groupPull++;
            }
        } while ($groupPull != 3);
    }
}
