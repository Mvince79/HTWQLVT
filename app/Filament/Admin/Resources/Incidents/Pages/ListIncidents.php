<?php

namespace App\Filament\Admin\Resources\Incidents\Pages;

use App\Filament\Admin\Resources\Incidents\IncidentResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Livewire\Attributes\On;

class ListIncidents extends ListRecords
{
    protected static string $resource = IncidentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }

    #[On('echo:admin-notifications,.incident.reported')]
    public function refreshOnIncidentReported(): void
    {
    }

    protected function getPollingInterval(): ?string
    {
        return '5s';
    }
}
