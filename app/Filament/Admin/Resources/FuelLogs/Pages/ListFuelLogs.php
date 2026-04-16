<?php

namespace App\Filament\Admin\Resources\FuelLogs\Pages;

use App\Filament\Admin\Resources\FuelLogs\FuelLogResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Livewire\Attributes\On;

class ListFuelLogs extends ListRecords
{
    protected static string $resource = FuelLogResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()];
    }

    #[On('echo:fleet-updates,.fuel.logged')]
    public function refreshOnFuelLogged(): void
    {
    }

    protected function getPollingInterval(): ?string
    {
        return '5s';
    }
}
