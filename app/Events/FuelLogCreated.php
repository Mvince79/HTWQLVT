<?php
namespace App\Events;
use App\Models\FuelLog;
use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class FuelLogCreated implements ShouldBroadcast
{
    use Dispatchable, SerializesModels;

    public function __construct(public FuelLog $fuelLog)
    {
    }

    public function broadcastOn(): array
    {
        return [new Channel('fleet-updates')];
    }

    public function broadcastAs(): string
    {
        return 'fuel.logged';
    }

    public function broadcastWith(): array
    {
        return [
            'id' => $this->fuelLog->id,
            // 'vehicle' => $this->fuelLog->vehicle->**license_plate** ?? 'N/A',
            'vehicle' => $this->fuelLog->vehicle?->license_plate ?? 'N/A',
            'driver' => $this->fuelLog->driver?->user?->name ?? 'N/A',
            'fuel_amount' => $this->fuelLog->fuel_amount,
            'created_at' => $this->fuelLog->created_at?->format('d/m/Y H:i'),
        ];
    }
}