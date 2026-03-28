<?php

namespace App\Filament\Admin\Resources\Drivers\Tables;

use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class DriversTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')->label('Tên tài xế')->searchable()->sortable(),
                TextColumn::make('plate_number')->label('Biển số')->searchable(),
                TextColumn::make('license_type')->label('Hạng bằng'),
                TextColumn::make('working_status')
                    ->label('Trạng thái')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'idle' => 'gray',
                        'assigned' => 'warning',
                        'delivering' => 'success',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'idle' => 'Đang rảnh',
                        'assigned' => 'Đã giao việc',
                        'delivering' => 'Đang đi hàng',
                        default => $state,
                    }),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }
}
