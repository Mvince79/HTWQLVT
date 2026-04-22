<?php

namespace App\Filament\Admin\Resources\Vehicles\Tables;

use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;

class VehiclesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([

                TextColumn::make('id')->label('ID')->sortable()->width('60px'),

                TextColumn::make('license_plate')
                    ->label('Biển số')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->weight('bold'),

                TextColumn::make('type')
                    ->label('Loại xe')
                    ->formatStateUsing(fn($state) => match ($state) {
                        'light_truck' => 'Xe tải nhẹ',
                        'medium_truck' => 'Xe tải trung',
                        'heavy_truck' => 'Xe tải nặng',
                        'container_truck' => 'Xe container',
                    })
                    ->description(
                        fn($record) =>
                        $record->max_weight ? "Tải: {$record->max_weight} tấn" : null
                    ),

                TextColumn::make('activeTrip.driver.user.name')
                    ->label('Tài xế hiện tại')
                    ->placeholder('— Trống —')
                    ->color('warning'),

                TextColumn::make('working_status')
                    ->label('Trạng thái')
                    ->badge()
                    ->color(fn($state) => match ($state) {
                        'available' => 'success',
                        'in_use' => 'warning',
                        'maintenance' => 'danger',
                    })
                    ->formatStateUsing(fn($state) => match ($state) {
                        'available' => 'Sẵn sàng',
                        'in_use' => 'Đang chạy',
                        'maintenance' => 'Bảo trì',
                    }),

                TextColumn::make('is_active')
                    ->label('Kích hoạt')
                    ->badge()
                    ->color(fn($state) => $state ? 'success' : 'gray')
                    ->formatStateUsing(fn($state) => $state ? 'Hoạt động' : 'Tắt'),

            ])

            ->filters([
                Tables\Filters\SelectFilter::make('working_status')
                    ->label('Trạng thái')
                    ->options([
                        'available' => 'Sẵn sàng',
                        'in_use' => 'Đang sử dụng',
                        'maintenance' => 'Đang bảo trì',
                    ]),
            ])

            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                DeleteBulkAction::make(),
            ]);

    }
}
