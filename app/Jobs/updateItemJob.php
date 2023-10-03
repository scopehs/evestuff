<?php

namespace App\Jobs;

use App\Models\Item;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;

class updateItemJob implements ShouldQueue
{
    use Dispatchable;use InteractsWithQueue;use Queueable;use SerializesModels;

    protected $itemID;

    /**
     * Create a new job instance.
     */
    public function __construct($itemID)
    {
        $this->itemID = $itemID;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $pull = 0;
        $itemID = $this->itemID;

        $item = Item::where('id', $itemID)->first();


        do {

            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'User-Agent' => 'evestuff.online evestuff@lol.com',
            ])->get('https://esi.evetech.net/latest/universe/types/' . $itemID . '/?datasource=tranquility&language=en');
            if ($response->successful()) {
                $pull = 3;
                $itemres = $response->json();
                $item->item_name = $itemres['name'];
                $item->save();
            } else {
                $headers = $response->headers();
                $sleep = $headers['X-Esi-Error-Limit-Reset'][0];
                sleep($sleep);
                $pull++;
            }
        } while ($pull != 3);
    }
}
