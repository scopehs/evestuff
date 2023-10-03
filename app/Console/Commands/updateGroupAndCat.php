<?php

namespace App\Console\Commands;

use App\Jobs\updateGroupJob;
use App\Models\Categorie;
use App\Models\Group;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class updateGroupAndCat extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:updateCatGroup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
     */
    public function handle()
    {
        echo "dance";
        $catPull = 0;
        do {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'User-Agent' => 'evestuff.online evestuff@lol.com',
            ])->get('https://esi.evetech.net/latest/universe/categories/?datasource=tranquility');

            if ($response->successful()) {
                $categories = $response->json();
                $catPull = 3;
                foreach ($categories as $category) {
                    echo $category . " - ";
                    $cat = Categorie::where('id', $category)->first();
                    if (!$cat) {
                        $new = new Categorie();
                        $new->save();
                        $new->id = $category;
                        $new->save();
                    }
                }
            } else {

                $headers = $response->headers();
                $sleep = $headers['X-Esi-Error-Limit-Reset'][0];
                sleep($sleep);
                $catPull++;
            }
        } while ($catPull != 3);



        $cats = Categorie::all();
        foreach ($cats as $cat) {
            $catGroup = 0;
            echo $cat->id . " - ";
            do {
                $response = Http::withHeaders([
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                    'User-Agent' => 'evestuff.online evestuff@lol.com',
                ])->get('https://esi.evetech.net/latest/universe/categories/' . $cat->id . '/?datasource=tranquility&language=en');
                if ($response->successful()) {
                    $catGroup = 3;
                    $res = $response->json();
                    $name = $res['name'];
                    $cat->name = $name;
                    $cat->save();
                    $groups = $res['groups'];
                    foreach ($groups as $group) {
                        $check = Group::where('id', $group)->first();
                        if (!$check) {
                            $new = new Group();
                            $new->id = $group;
                            $new->category_id = $cat->id;
                            $new->save();
                        }
                    }
                } else {

                    $headers = $response->headers();
                    $sleep = $headers['X-Esi-Error-Limit-Reset'][0];
                    sleep($sleep);
                    $catPull++;
                }
            } while ($catGroup != 3);
        }


        $groups = Group::all();
        foreach ($groups as $group) {

            updateGroupJob::dispatch($group->id);
        }
    }
}
