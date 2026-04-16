<?php

namespace App\Filament\Admin\Resources\Orders\Pages;

use App\Filament\Admin\Resources\Orders\OrderResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Livewire\Attributes\On;


class ListOrders extends ListRecords
{
    protected static string $resource = OrderResource::class;
    protected static ?string $title = 'Danh sách đơn hàng';

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()->label('Tạo đơn hàng'),
        ];
    }

    public function getBreadcrumbs(): array
    {
        return [];
    }

    #[On('echo:orders,.order.updated')]
    public function refreshOnOrderUpdated(): void
    {
    }
    protected function getListeners(): array
    {
        return [
            'echo:orders,.order.updated' => '$refresh',
        ];
    }

    protected function getPollingInterval(): ?string
    {
        return '3s';
    }
}
