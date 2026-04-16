<?php

namespace App\Filament\Admin\Resources\FuelLogs\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class FuelLogForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('vehicle_id')
                ->label('Phương tiện')
                ->relationship('vehicle', 'license_plate')
                ->searchable()
                ->preload()
                ->required(),

            Select::make('driver_id')
                ->label('Tài xế')
                ->relationship('driver.user', 'name')
                ->searchable()
                ->preload()
                ->required(),

            Select::make('trip_id')
                ->label('Chuyến xe')
                ->relationship('trip', 'id')
                ->searchable()
                ->preload()
                ->nullable(),

            TextInput::make('fuel_amount')
                ->label('Số lít (L)')
                ->numeric()
                ->minValue(0.1)
                ->maxValue(500)
                ->step('any')
                ->suffix('L')
                ->required(),

            Textarea::make('note')
                ->label('Ghi chú')
                ->rows(3)
                ->columnSpanFull(),
        ]);
    }
}