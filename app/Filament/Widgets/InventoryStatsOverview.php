<?php

namespace App\Filament\Widgets;

use App\Models\Customer;
use App\Models\ProductionEntry;
use App\Models\Sale;
use App\Models\Stock;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;

class InventoryStatsOverview extends StatsOverviewWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $totalSales = Sale::sum('total_amount');
        $totalProductions = ProductionEntry::sum('quantity');
        $totalCustomers = Customer::count();
        $stockValue = Stock::query()
            ->join('products', 'products.id', '=', 'stocks.product_id')
            ->sum(DB::raw('stocks.quantity * products.cost_price'));

        return [
            Stat::make('Total Sales', '₱' . number_format($totalSales, 2))
                ->description('Revenue from all sales')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success'),

            Stat::make('Total Productions', $totalProductions)
                ->description('Goods manufactured')
                ->descriptionIcon('heroicon-m-wrench-screwdriver')
                ->color('info'),

            Stat::make('Stock Value', '₱' . number_format($stockValue))
                ->description('Current inventory worth')
                ->descriptionIcon('heroicon-m-archive-box')
                ->color('primary'),

            Stat::make('Total Customers', $totalCustomers)
                ->description('Registered customers')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('secondary'),
        ];
    }
}
