<?php

namespace App\Filament\Admin\Resources\Trips\Pages;

use App\Filament\Admin\Resources\Trips\TripResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Livewire\Attributes\On;

class ListTrips extends ListRecords
{
    protected static string $resource = TripResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }

    #[On('echo:fleet-updates,.trip.updated')]
    public function refreshOnTripUpdated(): void
    {
    }

    protected function getPollingInterval(): ?string
    {
        return '5s';
    }
}
