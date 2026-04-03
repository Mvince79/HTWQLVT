<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Order;
use Carbon\Carbon;
use Filament\Forms\Components\Select;
use Filament\Widgets\ChartWidget;
use Filament\Widgets\ChartWidget\Concerns\HasFiltersSchema;
use Filament\Schemas\Schema;

class OrdersChart extends ChartWidget
{
    use HasFiltersSchema;

    protected ?string $heading = 'Tổng quan đơn hàng';

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
                ->whereYear('created_at', $selectedYear)
                ->whereMonth('created_at', $selectedMonth)
                ->selectRaw('DATE(created_at) as date, COUNT(*) as total')
                ->groupBy('date')
                ->orderBy('date')
                ->pluck('total', 'date');

            $labels = [];
            $values = [];

            foreach (range(1, $daysInMonth) as $day) {
                $date = Carbon::createFromDate($selectedYear, (int) $selectedMonth, $day)->toDateString();
                $labels[] = Carbon::parse($date)->format('d/m');
                $values[] = (int) ($data[$date] ?? 0);
            }

            return [
                'datasets' => [
                    [
                        'label' => 'Số đơn theo ngày',
                        'data' => $values,
                        'borderColor' => '#0ea5e9',
                        'backgroundColor' => 'rgba(14, 165, 233, 0.18)',
                        'pointBackgroundColor' => '#0ea5e9',
                        'pointRadius' => 2,
                        'fill' => true,
                        'tension' => 0.35,
                    ],
                ],
                'labels' => $labels,
            ];
        }

        $data = Order::query()
            ->whereYear('created_at', $selectedYear)
            ->selectRaw('MONTH(created_at) as month, COUNT(*) as total')
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month');

        $labels = [];
        $values = [];

        foreach (range(1, 12) as $month) {
            $labels[] = 'T' . $month;
            $values[] = (int) ($data[$month] ?? 0);
        }

        return [
            'datasets' => [
                [
                    'label' => 'Số đơn theo tháng',
                    'data' => $values,
                    'borderColor' => '#0ea5e9',
                    'backgroundColor' => 'rgba(14, 165, 233, 0.18)',
                    'pointBackgroundColor' => '#0ea5e9',
                    'pointRadius' => 2,
                    'fill' => true,
                    'tension' => 0.35,
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
                    'ticks' => [
                        'precision' => 0,
                    ],
                ],
            ],
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
