<?php

namespace App\Events;

use App\Models\Vehicle;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class VehicleStatusUpdated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public Vehicle $vehicle) {}

    public function broadcastOn(): array
    {
        return [new Channel('vehicle-updates')];
    }

    public function broadcastAs(): string
    {
        return 'vehicle.status.updated';
    }

    public function broadcastWith(): array
    {
        return [
            'vehicle_id'    => $this->vehicle->id,
            'license_plate' => $this->vehicle->license_plate,
            'status'        => $this->vehicle->status,
            'type'          => $this->vehicle->type,
        ];
    }
}