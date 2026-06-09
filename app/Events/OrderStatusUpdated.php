<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class OrderStatusUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $orderCode;
    public $status;

    public function __construct($orderCode, $status)
    {
        $this->orderCode = $orderCode;
        $this->status = $status;
    }

    public function broadcastOn()
    {
        return new Channel('orders.' . $this->orderCode);
    }

    public function broadcastAs()
    {
        return 'status.updated';
    }
}