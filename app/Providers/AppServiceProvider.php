<?php

namespace App\Providers;

use App\Models\ProductionEntry;
use App\Models\SaleItem;
use App\Models\StockAdjustment;
use App\Observers\ProductionEntriesObserver;
use App\Observers\SaleItemObserver;
use App\Observers\StockAdjustmentObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        ProductionEntry::observe(ProductionEntriesObserver::class);
        SaleItem::observe(SaleItemObserver::class);
        StockAdjustment::observe(StockAdjustmentObserver::class);
    }
}
