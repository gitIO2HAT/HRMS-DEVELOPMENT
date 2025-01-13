<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\Task;
use App\Models\Message;

class TaskCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $task;
    public $message;
    /**
     * Create a new event instance.
     */
    public function __construct(Task $task, Message $message)
    {
        $this->task = $task;
        $this->message = $message;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn() 
    {
       return ['my-channel'];
    }
}
