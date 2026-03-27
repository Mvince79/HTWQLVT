<?php

namespace App\Filament\Admin\Resources\Orders\Pages;

use App\Filament\Admin\Resources\Orders\OrderResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditOrder extends EditRecord
{
    protected static string $resource = OrderResource::class;

    public function getTitle(): string
    {
        return 'Xem đơn hàng #' . $this->record->code;
    }

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make()->modalHeading('Xóa đơn hàng'),
        ];
    }

    public function getBreadcrumbs(): array
    {
        return [];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('view', [
            'record' => $this->record,
        ]);
    }
}
