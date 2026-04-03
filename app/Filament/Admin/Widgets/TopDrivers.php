<?php

namespace App\Filament\Admin\Widgets;

use App\Filament\Admin\Resources\Drivers\DriverResource;
use Filament\Widgets\TableWidget;
use Filament\Tables;
use App\Models\Driver;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Eloquent\Model;
use Closure;

class TopDrivers extends TableWidget
{
    protected static ?string $heading = 'Top tài xế hoạt động';

    protected static ?int $sort = 40;

    protected int|string|array $columnSpan = [
        'md' => 1,
        'xl' => 1,
    ];

    protected ?string $pollingInterval = '60s';

    protected function getTableQuery(): Builder|Relation|null
    {
        return Driver::query()
            ->with('user')
            ->withCount('trips') // đếm số chuyến
            ->orderByDesc('trips_count') // sắp xếp giảm dần
            ->limit(5); // top 5
    }

    protected function isTablePaginationEnabled(): bool
    {
        return false;
    }

    protected function getTableColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('id')
                ->label('ID'),

            Tables\Columns\TextColumn::make('user.name')
                ->label('Tên tài xế'),

            Tables\Columns\TextColumn::make('plate_number')
                ->label('Biển số')
                ->placeholder('-'),

            Tables\Columns\TextColumn::make('trips_count')
                ->label('Số chuyến')
                ->badge()
                ->color('success'),

            Tables\Columns\TextColumn::make('working_status')
                ->label('Trạng thái')
                ->badge()
                ->formatStateUsing(fn (string $state): string => match ($state) {
                    'idle' => 'Rảnh',
                    'assigned' => 'Đã nhận đơn',
                    'delivering' => 'Đang giao',
                    'resting' => 'Đang nghỉ',
                    'inactive' => 'Ngưng hoạt động',
                    default => $state,
                })
                ->color(fn ($state) => match ($state) {
                    'idle' => 'gray',
                    'assigned' => 'warning',
                    'delivering' => 'success',
                    'resting' => 'info',
                    'inactive' => 'danger',
                }),

            Tables\Columns\TextColumn::make('updated_at')
                ->label('Cập nhật')
                ->since(),
        ];
    }

    protected function getTableRecordUrlUsing(): ?Closure
    {
        return fn (Model $record): string => DriverResource::getUrl('edit', ['record' => $record]);
    }
}
