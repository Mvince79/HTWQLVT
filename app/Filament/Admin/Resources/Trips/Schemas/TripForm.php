<?php

namespace App\Filament\Admin\Resources\Trips\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Utilities\Get;

use function now;

class TripForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->schema([
            Section::make('Điều phối vận hành (Operational Dispatch)')
                ->description('Thiết lập lộ trình và thời gian vận chuyển')
                ->schema([
                    Select::make('driver_id')
                        ->label('Tài xế phụ trách')
                        ->relationship('driver', 'id')
                        ->getOptionLabelFromRecordUsing(fn($record) => $record->user->name . " - " . $record->plate_number)
                        ->searchable()
                        ->preload()
                        ->required(),

                    Select::make('vehicle_id')
                        ->label('Xe vận chuyển')
                        ->relationship('vehicle', 'id')
                        ->getOptionLabelFromRecordUsing(fn($record) => $record->license_plate . " - " . $record->type)
                        ->searchable()
                        ->preload()
                        ->required(),

                    Select::make('order_id')
                        ->label('Mã vận đơn / Đơn hàng')
                        ->relationship('order', 'id')
                        ->getOptionLabelFromRecordUsing(fn($record) => $record->code)
                        ->searchable()
                        ->preload()
                        ->required(),

                    DateTimePicker::make('start_time')
                        ->label('Thời gian khởi hành')
                        ->default(now())
                        ->native(false)
                        ->required()
                        ->reactive(),

                    DateTimePicker::make('end_time')
                        ->label('Thời gian đến dự kiến (ETA)')
                        ->native(false)
                        ->minDate(fn(Get $get) => $get('start_time') ?? now())
                        ->helperText('Dựa trên khoảng cách ước tính của lộ trình.'),

                    Select::make('status')
                        ->label('Trạng thái vận hành')
                        ->options([
                            'not_started' => 'Đã lên lịch (Pending)',
                            'in_progress' => 'Đang thực hiện (Active)',
                            'completed' => 'Đã về bãi (Finished)',
                            'cancelled' => 'Sự cố/Hủy (Void)',
                        ])
                        ->default('not_started')
                        ->required()
                        ->native(false),
                ])->columns(2),
        ])->columns(1);
    }
}
