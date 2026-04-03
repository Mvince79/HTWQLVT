<?php

namespace App\Filament\Admin\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\Order;
use Carbon\Carbon;
use Filament\Forms\Components\Select;
use Filament\Widgets\ChartWidget\Concerns\HasFiltersSchema;
use Filament\Schemas\Schema;

class RevenueChart extends ChartWidget
{
    use HasFiltersSchema;

    protected ?string $heading = 'Tổng quan doanh thu';

    protected ?string $pollingInterval = '60s';

    protected function filtersSchema(Schema $schema): Schema
    {
        $years = collect(range((int) now()->year - 4, (int) now()->year + 1))
            ->reverse()
            ->mapWithKeys(fn (int $year) => [$year => (string) $year])
            ->all();

        return $schema->components([
            Select::make('year')
                ->label('Năm')
                ->options($years)
                ->default((int) now()->year)
                ->live(),

            Select::make('month')
                ->label('Tháng')
                ->options([
                    1 => 'Tháng 1',
                    2 => 'Tháng 2',
                    3 => 'Tháng 3',
                    4 => 'Tháng 4',
                    5 => 'Tháng 5',
                    6 => 'Tháng 6',
                    7 => 'Tháng 7',
                    8 => 'Tháng 8',
                    9 => 'Tháng 9',
                    10 => 'Tháng 10',
                    11 => 'Tháng 11',
                    12 => 'Tháng 12',
                ])
                ->placeholder('Tất cả tháng')
                ->live(),
        ]);
    }

    protected function getData(): array
    {
        $selectedYear = (int) ($this->filters['year'] ?? now()->year);
        $selectedMonth = $this->filters['month'] ?? null;

        if (filled($selectedMonth)) {
            $daysInMonth = Carbon::createFromDate($selectedYear, (int) $selectedMonth, 1)->daysInMonth;

            $data = Order::query()
                ->where('status', 'completed')
                ->whereYear('created_at', $selectedYear)
                ->whereMonth('created_at', $selectedMonth)
                ->selectRaw('DATE(created_at) as date, SUM(price) as total')
                ->groupBy('date')
                ->orderBy('date')
                ->pluck('total', 'date');

            $labels = [];
            $values = [];

            foreach (range(1, $daysInMonth) as $day) {
                $date = Carbon::createFromDate($selectedYear, (int) $selectedMonth, $day)->toDateString();
                $labels[] = Carbon::parse($date)->format('d/m');
                $values[] = (float) ($data[$date] ?? 0);
            }

            return [
                'datasets' => [
                    [
                        'label' => 'Doanh thu (VND)',
                        'data' => $values,
                        'backgroundColor' => '#22c55e',
                        'borderRadius' => 6,
                    ],
                ],
                'labels' => $labels,
            ];
        }

        $data = Order::query()
            ->where('status', 'completed')
            ->whereYear('created_at', $selectedYear)
            ->selectRaw('MONTH(created_at) as month, SUM(price) as total')
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month');

        $labels = [];
        $values = [];

        foreach (range(1, 12) as $month) {
            $labels[] = 'T' . $month;
            $values[] = (float) ($data[$month] ?? 0);
        }

        return [
            'datasets' => [
                [
                    'label' => 'Doanh thu (VND)',
                    'data' => $values,
                    'backgroundColor' => '#22c55e',
                    'borderRadius' => 6,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'display' => false,
                ],
            ],
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                ],
            ],
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
