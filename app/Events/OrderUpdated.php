<?php
namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class OrderUpdated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public int $orderId,
        public string $status,
        public string $orderCode,
    ) {
    }

    public function broadcastOn(): array
    {
        // Phát vào kênh 'orders'
        return [new Channel('orders')];
    }

    public function broadcastAs(): string
    {
        return 'order.updated';
    }

    public function broadcastWith(): array
    {
        return [
            'order_id' => $this->orderId,
            'order_code' => $this->orderCode,
            'status' => $this->status,
            'message' => "Đơn hàng {$this->orderCode} đã chuyển sang: " . $this->status,
        ];
    }
}