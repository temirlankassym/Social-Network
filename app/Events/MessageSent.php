<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageSent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $sender;
    public $receiver;
    public $message;

    public function __construct($userName, $receiver, $message)
    {
        $this->sender = $userName;
        $this->receiver = $receiver;
        $this->message = $message;
    }

    public function broadcastOn(): Channel
    {
        $array = str_split($this->sender . $this->receiver);
        sort($array);
        return new Channel('chat.'.implode("", $array));
    }

    public function broadcastWith()
    {
        return [
            'sender' => $this->sender,
            'receiver' => $this->receiver,
            'message' => $this->message,
        ];
    }
}
