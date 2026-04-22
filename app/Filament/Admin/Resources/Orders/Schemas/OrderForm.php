<?php

namespace App\Filament\Admin\Resources\Orders\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Filament\Forms\Components\DateTimePicker;

class OrderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('code')
                    ->label('Mã đơn')
                    ->disabled()
                    ->visible(fn($operation) => $operation === 'edit'),

                TextInput::make('cargo_name')
                    ->label('Tên đơn hàng')
                    ->required(),

                TextInput::make('cargo_weight')
                    ->label('Trọng lượng')
                    ->numeric()
                    ->required(),

                TextInput::make('price')
                    ->label('Giá')
                    ->numeric(),

                DateTimePicker::make('scheduled_date')
                    ->label('Thời hạn giao hàng (Deadline)')
                    ->timezone('Asia/Ho_Chi_Minh')
                    ->displayFormat('d/m/Y H:i')
                    ->native(false)
                    ->prefix('Trước')
                    ->minDate(now()->addHours(2))
                    ->required()
                    ->helperText('Thời gian cam kết hoàn tất giao hàng cho khách.'),

                Textarea::make('pickup_location')
                    ->label('Điểm lấy hàng')
                    ->required(),

                Textarea::make('delivery_location')
                    ->label('Điểm giao hàng')
                    ->required(),

                Select::make('status')
                    ->label('Tiến độ đơn hàng')
                    ->options([
                        'pending' => 'Chờ xử lý (Mới)',
                        'assigned' => 'Đã điều phối xe',
                        'delivering' => 'Đang trên đường giao',
                        'completed' => 'Đã giao hàng thành công',
                        'cancelled' => 'Đã hủy đơn',
                    ])
                    ->native(false)
                    ->selectablePlaceholder(false)
                    ->visible(fn($operation) => $operation === 'edit'),
            ]);
    }
}
