<?php

namespace App\Events;

use App\Models\Trip;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow; 


class TripUpdated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public Trip $trip)
    {
    }

    public function broadcastOn(): array
    {
        return [new Channel('fleet-updates')];
    }

    public function broadcastAs(): string
    {
        return 'trip.updated';
    }

    public function broadcastWith(): array
    {
        return [
            'trip_id' => $this->trip->id,
            'status' => $this->trip->status,
            'driver_name' => $this->trip->driver->user->name,
            'plate_number' => $this->trip->vehicle->license_plate ?? 'N/A',
        ];
    }
}