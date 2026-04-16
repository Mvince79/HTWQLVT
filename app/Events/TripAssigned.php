<?php

namespace App\Events;

use App\Models\Trip;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TripAssigned implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public Trip $trip) {}

    public function broadcastOn(): array
    {
        // Kênh riêng cho từng driver
        return [new Channel('driver.' . $this->trip->driver_id)];
    }

    public function broadcastAs(): string
    {
        return 'trip.assigned';
    }

    public function broadcastWith(): array
    {
        return [
            'trip_id'           => $this->trip->id,
            'pickup_location'   => $this->trip->order?->pickup_location ?? 'N/A',
            'delivery_location' => $this->trip->order?->delivery_location ?? 'N/A',
            'plate'             => $this->trip->vehicle?->license_plate ?? 'N/A',
            'status'            => $this->trip->status,
        ];
    }
}