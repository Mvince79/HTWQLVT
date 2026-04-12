<?php

namespace App\Filament\Admin\Resources\Incidents\Tables;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class IncidentsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('#')
                    ->sortable(),

                TextColumn::make('trip.id')
                    ->label('Chuyến #')
                    ->sortable(),

                TextColumn::make('driver.user.name')
                    ->label('Tài xế')
                    ->searchable(),

                TextColumn::make('type')
                    ->label('Loại sự cố')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'Tai nạn giao thông' => 'danger',
                        'Hỏng xe / cần hỗ trợ kỹ thuật' => 'warning',
                        'Kẹt xe / chậm tiến độ' => 'info',
                        default => 'gray',
                    }),

                TextColumn::make('note')
                    ->label('Mô tả')
                    ->limit(50)
                    ->tooltip(fn($record) => $record->note),

                TextColumn::make('created_at')
                    ->label('Thời gian')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('type')
                    ->label('Loại sự cố')
                    ->options([
                        'Hỏng xe / cần hỗ trợ kỹ thuật' => 'Hỏng xe / cần hỗ trợ kỹ thuật',
                        'Tai nạn giao thông' => 'Tai nạn giao thông',
                        'Kẹt xe / chậm tiến độ' => 'Kẹt xe / chậm tiến độ',
                        'Vấn đề với hàng hoá' => 'Vấn đề với hàng hoá',
                        'Sự cố khác' => 'Sự cố khác',
                    ]),
            ])
            ->defaultSort('created_at', 'desc');
    }
}