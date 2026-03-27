<?php

namespace App\Filament\Admin\Resources\Orders\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

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

                Textarea::make('pickup_location')
                    ->label('Điểm lấy hàng')
                    ->required(),

                Textarea::make('delivery_location')
                    ->label('Điểm giao hàng')
                    ->required(),

                Select::make('status')
                    ->label('Trạng thái')
                    ->options([
                        'pending' => 'Pending',
                        'assigned' => 'Assigned',
                        'delivering' => 'Delivering',
                        'completed' => 'Completed',
                        'cancelled' => 'Cancelled',
                    ])
                    ->visible(fn($operation) => $operation === 'edit'),
            ]);
    }
}
