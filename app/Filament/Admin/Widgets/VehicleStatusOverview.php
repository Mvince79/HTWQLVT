<?php

namespace App\Filament\Admin\Widgets;

use App\Filament\Admin\Resources\Vehicles\VehicleResource;
use App\Models\Vehicle;
use Filament\Tables;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Closure;

class VehicleStatusOverview extends TableWidget
{
    protected static ?string $heading = 'Tình trạng xe';

    protected static ?int $sort = 41;

    protected int|string|array $columnSpan = [
        'md' => 1,
        'xl' => 1,
    ];

    protected ?string $pollingInterval = '60s';

    protected function getTableQuery(): Builder|Relation|null
    {
        $statusRows = Vehicle::query()
            ->selectRaw("1 as id, 'driving' as status_key, COUNT(*) as total")
            ->where('working_status', 'in_use')
            ->unionAll(
                Vehicle::query()
                    ->selectRaw("2 as id, 'idle' as status_key, COUNT(*) as total")
                    ->where('working_status', 'available')
            )
            ->unionAll(
                Vehicle::query()
                    ->selectRaw("3 as id, 'incident' as status_key, COUNT(*) as total")
                    ->where('working_status', 'maintenance')
                    ->where('is_active', false)
            )
            ->unionAll(
                Vehicle::query()
                    ->selectRaw("4 as id, 'repairing' as status_key, COUNT(*) as total")
                    ->where('working_status', 'maintenance')
                    ->where('is_active', true)
            );

        return Vehicle::query()
            ->fromSub($statusRows, 'vehicle_status_summary')
            ->orderBy('id');
    }

    protected function isTablePaginationEnabled(): bool
    {
        return false;
    }

    protected function getTableColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('status_key')
                ->label('Tình trạng')
                ->badge()
                ->formatStateUsing(fn (string $state): string => match ($state) {
                    'driving' => 'Xe đang đi',
                    'idle' => 'Xe đang rảnh',
                    'incident' => 'Xe gặp sự cố',
                    'repairing' => 'Xe đang bảo trì',
                    default => $state,
                })
                ->color(fn (string $state): string => match ($state) {
                    'driving' => 'warning',
                    'idle' => 'success',
                    'incident' => 'danger',
                    'repairing' => 'info',
                    default => 'gray',
                }),

            Tables\Columns\TextColumn::make('total')
                ->label('Số lượng xe')
                ->badge()
                ->color('primary'),
        ];
    }

    protected function getTableRecordUrlUsing(): ?Closure
    {
        return function (Model $record): string {
            $params = [
                'tableFilters' => [
                    'working_status' => [
                        'value' => $record->status_key === 'idle' ? 'available' : ($record->status_key === 'driving' ? 'in_use' : 'maintenance'),
                    ],
                ],
            ];

            if ($record->status_key === 'incident') {
                $params['tableFilters']['is_active'] = ['value' => '0'];
            }

            if ($record->status_key === 'repairing') {
                $params['tableFilters']['is_active'] = ['value' => '1'];
            }

            return VehicleResource::getUrl('index') . '?' . http_build_query($params);
        };
    }
}
