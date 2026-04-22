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
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('id')->label('ID')->sortable(),
                TextColumn::make('driver.user.name')
                    ->label('Tài xế')
                    ->searchable()
                    ->description(fn($record) => $record->driver?->phone),
                TextColumn::make('vehicle.license_plate')
                    ->label('Phương tiện')
                    ->copyable()
                    ->searchable()
                    ->description(fn($record) => $record->vehicle?->type_label),
                TextColumn::make('order.code')
                    ->label('Đơn hàng')
                    ->copyable()
                    ->description(fn($record) => $record->order?->delivery_location),
                TextColumn::make('start_time')
                    ->label('Khởi hành')
                    ->dateTime('d/m H:i')
                    ->timezone('Asia/Ho_Chi_Minh')
                    ->placeholder('—'),
                TextColumn::make('end_time')
                    ->label('ETA')
                    ->dateTime('d/m H:i')
                    ->timezone('Asia/Ho_Chi_Minh')
                    ->placeholder('—')
                    ->color(
                        fn($record) =>
                        $record->end_time
                        && \Carbon\Carbon::parse($record->end_time)->isPast()
                        && $record->status === 'in_progress'
                        ? 'danger' : null
                    ),
                TextColumn::make('status')
                    ->label('Trạng thái')
                    ->badge()
                    ->color(fn(string $state) => match ($state) {
                        'not_started' => 'gray',
                        'in_progress' => 'warning',
                        'completed' => 'success',
                        'cancelled' => 'danger',
                    })
                    ->formatStateUsing(fn(string $state) => match ($state) {
                        'not_started' => 'Chờ khởi hành',
                        'in_progress' => 'Đang chạy',
                        'completed' => 'Hoàn thành',
                        'cancelled' => 'Đã hủy',
                    }),
            ])
            ->defaultSort('created_at', 'desc')
            ->poll('30s')
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
