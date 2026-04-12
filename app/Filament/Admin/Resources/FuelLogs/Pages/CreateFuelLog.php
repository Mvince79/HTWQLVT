<?php

namespace App\Filament\Admin\Resources\FuelLogs\Pages;

use App\Filament\Admin\Resources\FuelLogs\FuelLogResource;
use Filament\Resources\Pages\CreateRecord;

class CreateFuelLog extends CreateRecord
{
    protected static string $resource = FuelLogResource::class;
}