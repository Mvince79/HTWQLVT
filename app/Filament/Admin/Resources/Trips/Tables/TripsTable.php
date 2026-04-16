<?php

namespace App\Filament\Admin\Resources\Trips\Tables;

use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class TripsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->label('ID')->sortable(),
                TextColumn::make('driver.user.name')->label('Tài xế')->searchable(),
                TextColumn::make('vehicle.license_plate')
                    ->label('Biển số xe')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('order_id')->label('Mã đơn')->copyable(),
                TextColumn::make('status')
                    ->label('Trạng thái')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'not_started' => 'gray',
                        'in_progress' => 'warning',
                        'completed' => 'success',
                        'cancelled' => 'danger',
                        default => 'secondary',
                    })
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'not_started' => 'Chưa bắt đầu',
                        'in_progress' => 'Đang thực hiện',
                        'completed' => 'Hoàn thành',
                        'cancelled' => 'Đã hủy',
                        default => $state,
                    }),
                TextColumn::make('created_at')->label('Ngày tạo')->dateTime('d/m H:i'),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'not_started' => 'Chưa bắt đầu',
                        'in_progress' => 'Đang thực hiện',
                        'completed' => 'Hoàn thành',
                        'cancelled' => 'Đã hủy',
                    ]),
            ])
            ->recordActions([
                Action::make('start_now')
                    ->label('Bắt đầu ngay')
                    ->icon('heroicon-o-play-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->visible(fn($record) => $record->status === 'not_started')
                    ->action(function ($record) {
                        $record->update([
                            'status' => 'in_progress',
                            'start_time' => now(),
                        ]);

                        $record->driver->update([
                            'working_status' => 'delivering'
                        ]);

                        if ($record->vehicle) {
                            $record->vehicle->update([
                                'working_status' => 'in_use'
                            ]);
                        }
                    })
                    ->successNotificationTitle('Chuyến đi đã bắt đầu!'),
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }
}
