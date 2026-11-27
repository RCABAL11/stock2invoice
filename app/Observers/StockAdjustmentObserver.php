<?php

namespace App\Observers;

use App\Models\Stock;
use App\Models\StockAdjustment;

class StockAdjustmentObserver
{
    /**
     * Handle the StockAdjustment "created" event.
     */
    public function created(StockAdjustment $stockAdjustment): void
    {
        $change = $stockAdjustment->type === 'add'
            ? $stockAdjustment->quantity
            : -1 * $stockAdjustment->quantity;

        $this->adjustStock($stockAdjustment, $change);
    }

    /**
     * Handle the StockAdjustment "updated" event.
     */
    public function updated(StockAdjustment $stockAdjustment): void
    {
        if (! $stockAdjustment->wasChanged(['type', 'quantity'])) {
            return;
        }
        $originalType = $stockAdjustment->getOriginal('type');
        $originalQuantity = $stockAdjustment->getOriginal('quantity');
        $originalEffect = $originalType === 'add' ? $originalQuantity : -1 * $originalQuantity;
        $newEffect = $stockAdjustment->type === 'add' ? $stockAdjustment->quantity : -1 * $stockAdjustment->quantity;

        $this->adjustStock($stockAdjustment, $newEffect - $originalEffect);
    }

    /**
     * Handle the StockAdjustment "deleted" event.
     */
    public function deleted(StockAdjustment $stockAdjustment): void
    {
        $change = $stockAdjustment->type === 'add'
            ? -1 * $stockAdjustment->quantity
            : $stockAdjustment->quantity;

        $this->adjustStock($stockAdjustment, $change);
    }

    /**
     * Handle the StockAdjustment "restored" event.
     */
    public function restored(StockAdjustment $stockAdjustment): void
    {
        //
    }

    /**
     * Handle the StockAdjustment "force deleted" event.
     */
    public function forceDeleted(StockAdjustment $stockAdjustment): void
    {
        //
    }

    protected function adjustStock(StockAdjustment $stockAdjustment, int $quantityChange): void
    {
        if ($quantityChange === 0) {
            return;
        }


        $stock = Stock::firstOrNew([
            'product_id' => $stockAdjustment->product_id,
            'warehouse_id' => $stockAdjustment->warehouse_id,
        ]);

        $stock->quantity = ($stock->quantity ?? 0) + $quantityChange;
        $stock->save();
    }
}
