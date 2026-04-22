<?php

namespace App\Filament\Admin\Resources\Orders\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class OrdersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('code')
                    ->label('Mã đơn hàng')
                    ->copyable()
                    ->sortable()
                    ->searchable(),

                TextColumn::make('cargo_name')
                    ->label('Hàng hóa')
                    ->limit(25)
                    ->description(
                        fn($record) => $record->cargo_weight
                        ? number_format($record->cargo_weight) . ' kg'
                        : null
                    ),
                TextColumn::make('delivery_location')
                    ->label('Giao tới')
                    ->limit(30)
                    ->icon('heroicon-o-map-pin'),

                TextColumn::make('user.name')
                    ->label('Người tạo')
                    ->limit(20),

                TextColumn::make('scheduled_date')
                    ->label('Ngày giao')
                    ->dateTime('d/m/Y H:i')
                    ->timezone('Asia/Ho_Chi_Minh')
                    ->sortable()
                    ->color(
                        fn($record) =>
                        $record->scheduled_date
                        && \Carbon\Carbon::parse($record->scheduled_date)->diffInHours(now()) < 24
                        && $record->status === 'pending'
                        ? 'warning' : null
                    ),

                TextColumn::make('status')
                    ->label('Trạng thái')
                    ->badge()
                    ->color(fn(string $state) => match ($state) {
                        'pending' => 'warning',
                        'assigned' => 'primary',
                        'delivering' => 'info',
                        'completed' => 'success',
                        'cancelled' => 'danger',
                    })
                    ->formatStateUsing(fn(string $state) => match ($state) {
                        'pending' => 'Chờ xử lý',
                        'assigned' => 'Đã phân công',
                        'delivering' => 'Đang giao',
                        'completed' => 'Hoàn thành',
                        'cancelled' => 'Đã huỷ',
                    }),
            ])
            ->defaultSort('created_at', 'desc')

            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make(),

                EditAction::make()->label('Sửa'),

                DeleteAction::make()->modalHeading('Xóa đơn hàng'),

            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),

                ]),

            ]);
    }
}
