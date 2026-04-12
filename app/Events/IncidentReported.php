<?php

namespace App\Events;

use App\Models\Incident;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class IncidentReported implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;


    public $incident;


    public function __construct(Incident $incident)
    {
        $this->incident = $incident->load(['driver.user', 'trip']);
    }


    public function broadcastOn(): array
    {
        return [
            new Channel('admin-notifications'),
        ];
    }


    public function broadcastAs(): string
    {
        return 'incident.reported';
    }


    public function broadcastWith(): array
    {
        return [
            'id' => $this->incident->id,
            'driver_name' => $this->incident->driver->user->name ?? 'N/A',
            'type' => $this->incident->type,
            'note' => $this->incident->note,
            'time' => $this->incident->created_at->format('H:i d/m/Y'),
        ];
    }
}