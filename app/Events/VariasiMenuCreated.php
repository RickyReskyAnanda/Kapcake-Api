<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

use App\VariasiMenu;

class VariasiMenuCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $variasiMenu;

    // public $user;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(VariasiMenu $variasiMenu)
    {
        $this->variasiMenu = $variasiMenu;
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
