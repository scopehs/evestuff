<?php

namespace App\Console\Commands;

use App\Jobs\updateItemJob;
use App\Models\Item;
use Illuminate\Console\Command;

class updateItems extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:items';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $items = Item::whereNull('item_name')->get();
        foreach ($items as $item) {
            updateItemJob::dispatch($item->id);
        }
    }
}
