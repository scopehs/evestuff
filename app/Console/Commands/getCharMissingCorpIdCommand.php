<?php

namespace App\Console\Commands;

use App\Jobs\getLocalNamesJob;
use App\Models\Character;
use Illuminate\Console\Command;

class getCharMissingCorpIdCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:CharCorpDetails';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Will find all the chars with a corp and fill in the ID along with alliance if that is missing also';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $charIDs = Character::whereNull('corp_id')->pluck('id');
        foreach ($charIDs as $charID) {
            getLocalNamesJob::dispatch($charID);
        }
    }
}
