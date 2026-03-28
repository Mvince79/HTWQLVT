<?php

namespace App\Filament\Admin\Resources\Vehicles\Schemas;

use Filament\Schemas\Schema;
use Filament\Infolists\Components\TextEntry;

class VehicleInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([

            TextEntry::make('plate_number')
                ->label('Biển số'),

            TextEntry::make('type')
                ->label('Loại xe')
                ->formatStateUsing(fn($state) => match ($state) {
                    'light_truck' => 'Xe tải nhẹ',
                    'medium_truck' => 'Xe tải trung',
                    'heavy_truck' => 'Xe tải nặng',
                    'container_truck' => 'Xe container',
                }),

            TextEntry::make('working_status')
                ->label('Trạng thái')
                ->formatStateUsing(fn($state) => match ($state) {
                    'available' => 'Sẵn sàng',
                    'in_use' => 'Đang sử dụng',
                    'maintenance' => 'Đang bảo trì',
                }),

            TextEntry::make('is_active')
                ->label('Hoạt động')
                ->formatStateUsing(fn($state) => $state ? 'Hoạt động' : 'Ngưng hoạt động'),

        ]);
    }
}
