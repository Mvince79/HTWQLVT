<?php

namespace App\Observers;

use App\Events\TripAssigned;
use App\Events\TripUpdated;
use App\Events\VehicleStatusUpdated;
use App\Models\Trip;

class TripObserver
{
    public function created(Trip $trip): void
    {
        if ($trip->driver_id) {
            $trip->load(['order', 'vehicle']);
            event(new TripAssigned($trip));
        }
    }

    public function updated(Trip $trip): void
    {
        $trip->loadMissing(['driver.user', 'vehicle']);

        event(new TripUpdated($trip));

        if ($trip->wasChanged('status')) {
            if (in_array($trip->status, ['completed', 'cancelled'])) {
                $vehicle = $trip->vehicle;
                if ($vehicle) {
                    $vehicle->refresh();
                    event(new VehicleStatusUpdated($vehicle));
                }
            }
        }

        if ($trip->wasChanged('driver_id') && $trip->driver_id) {
            event(new TripAssigned($trip));
        }
    }
}
