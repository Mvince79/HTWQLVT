<?php

namespace App\Filament\Admin\Resources\Vehicles\Tables;

use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables;
use Filament\Tables\Table;

class VehiclesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([

                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),

                Tables\Columns\TextColumn::make('license_plate')
                    ->label('Biển số')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('type')
                    ->label('Loại xe')
                    ->formatStateUsing(fn($state) => match ($state) {
                        'light_truck' => 'Xe tải nhẹ',
                        'medium_truck' => 'Xe tải trung',
                        'heavy_truck' => 'Xe tải nặng',
                        'container_truck' => 'Xe container',
                    }),

                Tables\Columns\TextColumn::make('working_status')
                    ->label('Trạng thái')
                    ->badge()
                    ->color(fn($state) => match ($state) {
                        'available' => 'success',
                        'in_use' => 'warning',
                        'maintenance' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn($state) => match ($state) {
                        'available' => 'Sẵn sàng',
                        'in_use' => 'Đang sử dụng',
                        'maintenance' => 'Đang bảo trì',
                        default => $state,
                    }),

                Tables\Columns\TextColumn::make('is_active')
                    ->label('Hoạt động')
                    ->badge()
                    ->color(fn($state) => $state ? 'success' : 'danger')
                    ->formatStateUsing(fn($state) => $state ? 'Hoạt động' : 'Ngưng hoạt động'),

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
