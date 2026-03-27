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
