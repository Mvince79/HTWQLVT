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
                TextColumn::make('driver.plate_number')->label('Xe'),
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
                    ->requiresConfirmation() // Hỏi lại cho chắc
                    // Chỉ hiện nút này nếu chuyến đi đang ở trạng thái 'not_started'
                    ->visible(fn($record) => $record->status === 'not_started')
                    ->action(function ($record) {
                        // 1. Cập nhật chuyến đi
                        $record->update([
                            'status' => 'in_progress',
                            'start_time' => now(),
                        ]);

                        // 2. Cập nhật trạng thái tài xế sang 'đang giao hàng'
                        $record->driver->update([
                            'working_status' => 'delivering'
                        ]);
                    })
                    ->successNotificationTitle('Chuyến đi đã bắt đầu!'),
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }
}
