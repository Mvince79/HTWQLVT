<?php

namespace App\Services;

use App\Models\Trip;
use Carbon\Carbon;

class TripConflictValidator
{
    /**
     * Kiểm tra tài xế có bị trùng lịch không
     */
    public static function driverHasConflict(
        int $driverId,
        Carbon $start,
        Carbon $end,
        ?int $excludeTripId = null  // dùng khi edit
    ): ?Trip {
        return Trip::where('driver_id', $driverId)
            ->whereNotIn('status', ['completed', 'cancelled'])
            ->when($excludeTripId, fn($q) => $q->where('id', '!=', $excludeTripId))
            ->where(function ($q) use ($start, $end) {
                // Overlap: start_a < end_b AND end_a > start_b
                $q->where('start_time', '<', $end)
                    ->where('end_time', '>', $start);
            })
            ->with('vehicle')
            ->first();
    }

    /**
     * Kiểm tra xe có bị trùng lịch không
     */
    public static function vehicleHasConflict(
        int $vehicleId,
        Carbon $start,
        Carbon $end,
        ?int $excludeTripId = null
    ): ?Trip {
        return Trip::where('vehicle_id', $vehicleId)
            ->whereNotIn('status', ['completed', 'cancelled'])
            ->when($excludeTripId, fn($q) => $q->where('id', '!=', $excludeTripId))
            ->where(function ($q) use ($start, $end) {
                $q->where('start_time', '<', $end)
                    ->where('end_time', '>', $start);
            })
            ->with('driver.user')
            ->first();
    }
}