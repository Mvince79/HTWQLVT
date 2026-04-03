<?php

namespace App\Filament\Admin\Widgets;

use App\Filament\Admin\Resources\Drivers\DriverResource;
use App\Filament\Admin\Resources\Orders\OrderResource;
use Carbon\Carbon;
use App\Models\Driver;
use App\Models\Order;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected ?string $pollingInterval = '30s';

    protected function getStats(): array
    {
        $currentYear = (int) now()->year;
        $currentMonth = (int) now()->month;

        $dailyOrders = Order::query()
            ->where('created_at', '>=', now()->subDays(6)->startOfDay())
            ->selectRaw('DATE(created_at) as date, COUNT(*) as total')
            ->groupBy('date')
            ->pluck('total', 'date');

        $dailyRevenue = Order::query()
            ->where('status', 'completed')
            ->where('created_at', '>=', now()->subDays(6)->startOfDay())
            ->selectRaw('DATE(created_at) as date, SUM(price) as total')
            ->groupBy('date')
            ->pluck('total', 'date');

        $orderTrend = collect(range(0, 6))
            ->map(fn (int $offset) => (int) ($dailyOrders[Carbon::now()->subDays(6 - $offset)->toDateString()] ?? 0))
            ->all();

        $revenueTrend = collect(range(0, 6))
            ->map(fn (int $offset) => (float) ($dailyRevenue[Carbon::now()->subDays(6 - $offset)->toDateString()] ?? 0))
            ->all();

        $completedRevenue = (float) Order::query()->where('status', 'completed')->sum('price');
        $allOrdersUrl = $this->buildOrderUrl([
            'created_year' => $currentYear,
            'created_month' => $currentMonth,
        ]);
        $completedOrdersUrl = $this->buildOrderUrl([
            'created_year' => $currentYear,
            'created_month' => $currentMonth,
            'status' => 'completed',
        ]);
        $deliveringOrdersUrl = $this->buildOrderUrl([
            'created_year' => $currentYear,
            'created_month' => $currentMonth,
            'status' => 'delivering',
        ]);
        $deliveringDriversUrl = DriverResource::getUrl('index') . '?' . http_build_query([
            'tableFilters' => [
                'working_status' => [
                    'value' => 'delivering',
                ],
            ],
        ]);

        return [
            Stat::make('Tổng đơn', Order::count())
                ->description('Cập nhật theo thời gian thực')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->chart($orderTrend)
                ->color('primary')
                ->url($allOrdersUrl),

            Stat::make('Doanh thu hoàn thành', number_format($completedRevenue, 0, ',', '.') . ' VND')
                ->description('Tổng đơn đã hoàn tất')
                ->descriptionIcon('heroicon-m-banknotes')
                ->chart($revenueTrend)
                ->color('success')
                ->url($completedOrdersUrl),

            Stat::make('Đang giao', Order::where('status', 'delivering')->count())
                ->description('Đơn đang trên đường vận chuyển')
                ->descriptionIcon('heroicon-m-truck')
                ->color('warning')
                ->url($deliveringOrdersUrl),

            Stat::make('Driver đang chạy', Driver::where('working_status', 'delivering')->count())
                ->description('Tài xế hiện đang giao hàng')
                ->descriptionIcon('heroicon-m-user-circle')
                ->color('danger')
                ->url($deliveringDriversUrl),
        ];
    }

    protected function buildOrderUrl(array $filters): string
    {
        $query = ['tableFilters' => []];

        if (array_key_exists('created_year', $filters)) {
            $query['tableFilters']['created_year'] = ['value' => $filters['created_year']];
        }

        if (array_key_exists('created_month', $filters)) {
            $query['tableFilters']['created_month'] = ['value' => $filters['created_month']];
        }

        if (array_key_exists('status', $filters)) {
            $query['tableFilters']['status'] = ['value' => $filters['status']];
        }

        return OrderResource::getUrl('index') . '?' . http_build_query($query);
    }
}
