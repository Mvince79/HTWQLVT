<?php

namespace App\Filament\Admin\Resources\Incidents\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class IncidentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('trip_id')
                ->label('Chuyến #')
                ->relationship('trip', 'id')
                ->searchable()
                ->required(),

            Select::make('driver_id')
                ->label('Tài xế')
                ->relationship('driver.user', 'name')
                ->searchable()
                ->required(),

            Select::make('type')
                ->label('Loại sự cố')
                ->options([
                    'Hỏng xe / cần hỗ trợ kỹ thuật' => 'Hỏng xe / cần hỗ trợ kỹ thuật',
                    'Tai nạn giao thông' => 'Tai nạn giao thông',
                    'Kẹt xe / chậm tiến độ' => 'Kẹt xe / chậm tiến độ',
                    'Vấn đề với hàng hoá' => 'Vấn đề với hàng hoá',
                    'Sự cố khác' => 'Sự cố khác',
                ])
                ->required(),

            Textarea::make('note')
                ->label('Mô tả chi tiết')
                ->required()
                ->columnSpanFull(),
        ])->columns(1);
    }
}