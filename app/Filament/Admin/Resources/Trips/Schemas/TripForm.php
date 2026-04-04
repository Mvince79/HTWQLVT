<?php

namespace App\Filament\Admin\Resources\Trips\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class TripForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->schema([
            Section::make('Điều phối vận chuyển')
                ->schema([
                    Select::make('driver_id')
                        ->label('Tài xế phụ trách')
                        ->relationship('driver', 'id')
                        ->getOptionLabelFromRecordUsing(fn($record) => $record->user->name . " - " . $record->plate_number)
                        ->searchable()
                        ->preload()
                        ->required(),

                    TextInput::make('order_id')
                        ->label('Mã vận đơn / Đơn hàng')
                        ->required(),

                    DateTimePicker::make('start_time')
                        ->label('Thời gian bắt đầu')
                        ->default(now())
                        ->required(),

                    DateTimePicker::make('end_time')
                        ->label('Thời gian kết thúc dự kiến'),

                    Select::make('status')
                        ->label('Trạng thái')
                        ->options([
                            'not_started' => 'Hẹn trước (Chưa đi)',
                            'in_progress' => 'Đang thực hiện',
                            'completed' => 'Hoàn thành',
                            'cancelled' => 'Đã hủy',
                        ])
                        ->default('not_started')
                        ->required(),
                ])
        ])->columns(1);
    }
}
