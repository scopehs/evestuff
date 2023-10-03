<?php

namespace App\Console\Commands;

use App\Jobs\UpdateCharInfo as JobsUpdateCharInfo;
use App\Models\Character;
use Illuminate\Console\Command;

class UpdateCharInfo extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:charInfo';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Will get the older characters and update their info 200 at a time.';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $chars = Character::orderBy('updated_at')->limit(200)->get();

        foreach ($chars as $char) {
            JobsUpdateCharInfo::dispatch($char->id)->onQueue('alliance');
        }
    }
}
