<?php

namespace App\Filament\Admin\Resources\FuelLogs\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class FuelLogsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('#')
                    ->sortable(),

                TextColumn::make('vehicle.license_plate')
                    ->label('Biển số xe')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('driver.user.name')
                    ->label('Tài xế')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('trip_id')
                    ->label('Chuyến #')
                    ->default('—')
                    ->sortable(),

                TextColumn::make('fuel_amount')
                    ->label('Số lít')
                    ->suffix(' L')
                    ->sortable(),

                TextColumn::make('note')
                    ->label('Ghi chú')
                    ->limit(40)
                    ->tooltip(fn($record) => $record->note),

                TextColumn::make('created_at')
                    ->label('Thời gian')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('vehicle_id')
                    ->label('Phương tiện')
                    ->relationship('vehicle', 'license_plate')
                    ->searchable()
                    ->preload(),

                SelectFilter::make('driver_id')
                    ->label('Tài xế')
                    ->relationship('driver.user', 'name')
                    ->searchable()
                    ->preload(),
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
            ])
            ->defaultSort('created_at', 'desc');
    }
}