<?php

namespace App\Filament\Admin\Resources\Vehicles\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\TextInput;

class VehicleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([

            TextInput::make('license_plate')
                ->label('Biển số xe')
                ->required()
                ->maxLength(20)
                ->unique(ignoreRecord: true),

            Select::make('type')
                ->label('Loại xe')
                ->options([
                    'light_truck' => 'Xe tải nhẹ',
                    'medium_truck' => 'Xe tải trung',
                    'heavy_truck' => 'Xe tải nặng',
                    'container_truck' => 'Xe container',
                ])
                ->required(),

            Select::make('working_status')
                ->label('Trạng thái')
                ->options([
                    'available' => 'Sẵn sàng',
                    'in_use' => 'Đang sử dụng',
                    'maintenance' => 'Đang bảo trì',
                ])
                ->required(),

            Toggle::make('is_active')
                ->label('Đang hoạt động')
                ->default(true),

        ]);
    }
}