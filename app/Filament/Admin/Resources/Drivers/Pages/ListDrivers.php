<?php

namespace App\Filament\Admin\Resources\Drivers\Pages;

use App\Filament\Admin\Resources\Drivers\DriverResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListDrivers extends ListRecords
{
    protected static string $resource = DriverResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }

    protected function getPollingInterval(): ?string
    {
        return '5s';
    }
}
