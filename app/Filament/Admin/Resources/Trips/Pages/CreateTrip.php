<?php

namespace App\Filament\Admin\Resources\Trips\Pages;

use App\Filament\Admin\Resources\Trips\TripResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use App\Models\Driver;
use App\Services\TripConflictValidator;
use Carbon\Carbon;

class CreateTrip extends CreateRecord
{
    protected static string $resource = TripResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        return DB::transaction(function () use ($data) {

            Driver::where('id', $data['driver_id'])->lockForUpdate()->first();

            $start = Carbon::parse($data['start_time']);
            $end = Carbon::parse($data['end_time']);

            if (TripConflictValidator::driverHasConflict($data['driver_id'], $start, $end)) {
                throw ValidationException::withMessages([
                    'driver_id' => 'Tài xế bị trùng lịch'
                ]);
            }

            if (TripConflictValidator::vehicleHasConflict($data['vehicle_id'], $start, $end)) {
                throw ValidationException::withMessages([
                    'vehicle_id' => 'Xe bị trùng lịch'
                ]);
            }

            return $data;
        });
    }
}