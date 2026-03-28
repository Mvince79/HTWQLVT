<?php

namespace App\Filament\Admin\Resources\Users\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->sortable(),
                TextColumn::make('name')->sortable()->searchable(),
                TextColumn::make('email')->sortable()->searchable(),
                TextColumn::make('phone')->sortable()->searchable(),
                TextColumn::make('role')->sortable()->searchable(),
                IconColumn::make('is_active')
                    ->label('Trạng thái')
                    ->boolean() // Tự động biến 1 thành tích xanh, 0 thành X đỏ
                    ->sortable(),
                TextColumn::make('created_at')->dateTime('d/m/Y H:i')->sortable()->searchable(),
                TextColumn::make('updated_at')->dateTime('d/m/Y H:i')->sortable()->searchable(),
            ])
            ->filters([
                // Lọc theo Role
                SelectFilter::make('role')
                    ->label('Vai trò')
                    ->options([
                        'admin' => 'Quản trị viên',
                        'driver' => 'Tài xế',
                    ]),
                // Lọc theo trạng thái Hoạt động (Đúng/Sai/Tất cả)
                TernaryFilter::make('is_active')
                    ->label('Trạng thái hoạt động'),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
