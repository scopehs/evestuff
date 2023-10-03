<?php

namespace App\Http\Controllers\Helpers;

use Illuminate\Support\Facades\Http;

if (! function_exists('discordPost')) {
    function discordPost($webhook, $content, $embeds)
    {
        /*
         *  'content' => "Message here.",
         *   'embeds' => [
         *       [
         *           'title' => "An awesome new notification!",
         *           'description' => "Discord Webhooks are great!",
         *           'color' => '7506394',
         *       ]
         */

        return Http::post(
            $webhook,
            [
            'content' => $content,
            'embeds' => [$embeds],
        ],
        );
    }
}
