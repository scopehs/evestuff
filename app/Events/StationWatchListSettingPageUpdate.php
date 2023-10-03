<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class StationWatchListSettingPageUpdate implements ShouldBroadcastNow
{
    use Dispatchable;use InteractsWithSockets;use SerializesModels;
    public $flag;

    /**
     * Create a new event instance.
     */
    public function __construct($flag)
    {
        $this->flag = $flag;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('stationwatchlistsetuppage'),
        ];
    }
}
