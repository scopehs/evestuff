<?php

namespace App\Http\Controllers;

use App\Models\FeedBack;
use App\Models\User;
use Illuminate\Http\Request;

use function App\Http\Controllers\Helpers\discordPost;

class FeedBackController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $feedback = [];
        $feed = FeedBack::with('user')->get();
        foreach ($feed as $feed) {
            $data = [];
            $time = fixtime($feed->created_at);
            $data = [
                'id' => $feed->id,
                'user_id' => $feed->user_id,
                'user_name' => $feed->user->name,
                'text' => $feed->text,
                'created' => $time,
            ];
            array_push($feedback, $data);
        }
        // $test = User::has('feedback')->get();

        return ['feedback' => $feedback];
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $feedback = FeedBack::create($request->all());
        $this->discord($feedback);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        FeedBack::destroy($id);
    }

    public function discord($request)
    {
        $feedback = Feedback::where('id', $request->id)
            ->with('user')
            ->first();

        // Dev this further to allow for webhooks to be added per function.
        // This is the webhook url, it should be hashed in the DB.
        $webhook = 'https://discord.com/api/webhooks/895459274476650536/_IBtb1l80oQt0whUOIoGOj_FGqlVfSuR9zArFshoXwVdY3PyhkKGyVaxvAE3FfU5feOn';

        // Header
        $content = '@JohnMonty - EVESTUFF - new feedback report. - EVESTUFF';

        // Body
        /*
         *  'content' => "Message here.",
         *   'embeds' => [
         *       [
         *           'title' => "An awesome new notification!",
         *           'description' => "Discord Webhooks are great!",
         *           'color' => '7506394',
         *       ]
         */

        $embeds = [
            'title' => $feedback->user->name.' : '.$feedback->title,
            'description' => $feedback->text,
            'color' => '7506394',
        ];

        discordPost($webhook, $content, $embeds);
    }
}
