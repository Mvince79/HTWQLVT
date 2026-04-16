<?php

namespace App\Observers;

use App\Events\VehicleStatusUpdated;
use App\Models\Vehicle;

class VehicleObserver
{
    public function updated(Vehicle $vehicle): void
    {
        if ($vehicle->wasChanged('status')) {
            event(new VehicleStatusUpdated($vehicle));
        }
    }
}