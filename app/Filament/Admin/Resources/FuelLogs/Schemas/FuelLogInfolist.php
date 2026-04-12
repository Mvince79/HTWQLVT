<?php

namespace App\Filament\Admin\Resources\FuelLogs\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class FuelLogInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Thông tin nhiên liệu')
                ->columns(2)
                ->schema([
                    TextEntry::make('vehicle.plate_number')
                        ->label('Biển số xe'),

                    TextEntry::make('driver.user.name')
                        ->label('Tài xế'),

                    TextEntry::make('trip_id')
                        ->label('Chuyến #')
                        ->default('—'),

                    TextEntry::make('fuel_amount')
                        ->label('Số lít')
                        ->suffix(' L'),

                    TextEntry::make('created_at')
                        ->label('Thời gian')
                        ->dateTime('d/m/Y H:i'),

                    TextEntry::make('note')
                        ->label('Ghi chú')
                        ->default('—')
                        ->columnSpanFull(),
                ]),
        ]);
    }
}