<?php

namespace App\Filament\Admin\Resources\FuelLogs\Pages;

use App\Filament\Admin\Resources\FuelLogs\FuelLogResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditFuelLog extends EditRecord
{
    protected static string $resource = FuelLogResource::class;

    protected function getHeaderActions(): array
    {
        return [DeleteAction::make()];
    }
}