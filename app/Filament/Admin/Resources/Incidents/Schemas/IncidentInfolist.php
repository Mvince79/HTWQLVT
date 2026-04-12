<?php

namespace App\Filament\Admin\Resources\Incidents\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class IncidentInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            TextEntry::make('id')->label('#'),
            TextEntry::make('trip.id')->label('Chuyến #'),
            TextEntry::make('driver.user.name')->label('Tài xế'),
            TextEntry::make('type')->label('Loại sự cố'),
            TextEntry::make('note')
                ->label('Mô tả')
                ->columnSpanFull(),
            TextEntry::make('created_at')
                ->label('Thời gian báo cáo')
                ->dateTime('d/m/Y H:i'),
        ]);
    }
}