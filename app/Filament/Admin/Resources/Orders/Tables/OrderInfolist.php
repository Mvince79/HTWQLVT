<?php
namespace App\Filament\Resources\Orders\Tables;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class OrderInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Thông tin đơn hàng')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('code')
                            ->label('Mã đơn hàng'),

                        TextEntry::make('status')
                            ->label('Trạng thái')
                            ->badge(),

                        TextEntry::make('cargo_name')
                            ->label('Tên đơn hàng'),

                        TextEntry::make('cargo_weight')
                            ->label('Trọng lượng (kg)'),

                        TextEntry::make('price')
                            ->label('Giá')
                            ->money('VND'),

                        TextEntry::make('user.name')
                            ->label('Người tạo'),

                        TextEntry::make('created_at')
                            ->label('Ngày tạo')
                            ->dateTime(),

                        TextEntry::make('updated_at')
                            ->label('Cập nhật lần cuối')
                            ->dateTime(),

                        TextEntry::make('scheduled_date')
                            ->label('Ngày giao hàng')
                            ->dateTime(),
                    ]),

                Section::make('Địa điểm')
                    ->schema([
                        TextEntry::make('pickup_location')
                            ->label('Điểm lấy hàng'),

                        TextEntry::make('delivery_location')
                            ->label('Điểm giao hàng'),
                    ]),
            ]);
    }

}
