<?php

namespace App\Events;

use Auth;
use App\Bisnis;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class BisnisCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $bisnis;

    // public $user;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Bisnis $bisnis)
    {
        $this->bisnis = $bisnis;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
