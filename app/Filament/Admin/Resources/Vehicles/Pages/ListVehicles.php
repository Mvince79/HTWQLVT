<?php

namespace App\Filament\Admin\Resources\Vehicles\Pages;

use App\Filament\Admin\Resources\Vehicles\VehicleResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Livewire\Attributes\On;

class ListVehicles extends ListRecords
{
    protected static string $resource = VehicleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }

    #[On('echo:vehicle-updates,.vehicle.status.updated')]
    public function refreshOnVehicleUpdated(): void
    {
    }

    protected function getPollingInterval(): ?string
    {
        return '5s';
    }
}
