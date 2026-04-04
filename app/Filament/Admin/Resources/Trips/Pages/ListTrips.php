<?php

namespace App\Filament\Admin\Resources\Trips\Pages;

use App\Filament\Admin\Resources\Trips\TripResource;
use App\Filament\Admin\Widgets\TripCalendarWidget;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListTrips extends ListRecords
{
    protected static string $resource = TripResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            TripCalendarWidget::class,
        ];
    }

    protected function getListeners(): array
    {
        return [
            'echo:trips,.trip.changed' => '$refresh',
        ];
    }
}
