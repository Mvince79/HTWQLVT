<?php

namespace App\Filament\Admin\Resources\Trips\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Utilities\Get;
use App\Models\Order;
use Filament\Schemas\Components\Utilities\Set;


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
                        ->relationship('order', 'code')
                        ->searchable()
                        ->preload()
                        ->required()
                        ->reactive()
                        ->live()
                        ->afterStateUpdated(function ($state, Set $set) {
                            $order = Order::find($state);
                            $set('order_deadline', $order?->scheduled_date);
                            $set('end_time', null);
                        }),
                    DateTimePicker::make('start_time')
                        ->label('Thời gian khởi hành')
                        ->default(now())
                        ->native(false)
                        ->required()
                        ->reactive()
                        ->live()
                        ->minDate(now())
                        ->maxDate(function (Get $get) {
                            $orderId = $get('order_id');
                            return $orderId ? Order::find($orderId)?->scheduled_date : null;
                        })
                        ->afterStateUpdated(function ($state, Set $set, Get $get) {
                            $deadline = Order::find($get('order_id'))?->scheduled_date;

                            if ($deadline && $state > $deadline) {
                                $set('start_time', $deadline);
                            }
                        }),

                    DateTimePicker::make('end_time')
                        ->label('Thời gian đến dự kiến (ETA)')
                        ->native(false)
                        ->required()
                        ->minDate(fn(Get $get) => $get('start_time') ?? now())
                        ->maxDate(function (Get $get) {
                            $orderId = $get('order_id');
                            if (!$orderId)
                                return null;

                            return Order::find($orderId)?->scheduled_date;
                        })
                        ->after('start_time')
                        ->helperText(function (Get $get) {
                            $orderId = $get('order_id');
                            if (!$orderId)
                                return 'Dựa trên khoảng cách ước tính.';

                            $deadline = Order::find($orderId)?->scheduled_date;
                            return $deadline
                                ? "Lưu ý: Phải giao trước " . \Carbon\Carbon::parse($deadline)->format('d/m/Y H:i')
                                : 'Dựa trên khoảng cách ước tính.';
                        }),

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
