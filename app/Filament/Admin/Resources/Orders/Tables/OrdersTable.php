<?php

namespace App\Filament\Admin\Resources\Orders\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class OrdersTable
{
    public static function configure(Table $table): Table
    {
        $years = collect(range((int) now()->year - 4, (int) now()->year + 1))
            ->reverse()
            ->mapWithKeys(fn (int $year) => [$year => (string) $year])
            ->all();

        return $table
            ->columns([
                TextColumn::make('code')->label('Mã đơn hàng')->sortable(),

                TextColumn::make('cargo_name')->label('Tên')->limit(20),

                TextColumn::make('user.name')
                    ->label('Người tạo')
                    ->limit(20),

                TextColumn::make('scheduled_date')
                    ->label('Ngày giao')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),

                BadgeColumn::make('status')
                    ->label('Trạng thái')
                    ->colors([
                        'warning' => 'pending',
                        'primary' => 'assigned',
                        'info' => 'delivering',
                        'success' => 'completed',
                        'danger' => 'cancelled',
                    ]),
            ])
            ->filters([
                SelectFilter::make('created_year')
                    ->label('Năm')
                    ->query(fn ($query, array $data) => $query->when(
                        filled($data['value'] ?? null),
                        fn ($query) => $query->whereYear('created_at', $data['value'])
                    ))
                    ->options($years),

                SelectFilter::make('created_month')
                    ->label('Tháng')
                    ->query(fn ($query, array $data) => $query->when(
                        filled($data['value'] ?? null),
                        fn ($query) => $query->whereMonth('created_at', $data['value'])
                    ))
                    ->options([
                        '1' => 'Tháng 1',
                        '2' => 'Tháng 2',
                        '3' => 'Tháng 3',
                        '4' => 'Tháng 4',
                        '5' => 'Tháng 5',
                        '6' => 'Tháng 6',
                        '7' => 'Tháng 7',
                        '8' => 'Tháng 8',
                        '9' => 'Tháng 9',
                        '10' => 'Tháng 10',
                        '11' => 'Tháng 11',
                        '12' => 'Tháng 12',
                    ]),

                SelectFilter::make('status')
                    ->label('Trạng thái')
                    ->options([
                        'pending' => 'Chờ xử lý',
                        'assigned' => 'Đã phân công',
                        'delivering' => 'Đang giao',
                        'completed' => 'Hoàn thành',
                        'cancelled' => 'Đã hủy',
                    ]),
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
