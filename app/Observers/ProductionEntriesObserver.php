<?php

namespace App\Observers;

use App\Models\ProductionEntry;
use App\Models\Stock;
use App\Models\Stocks;

class ProductionEntriesObserver
{
    public function created(ProductionEntry $productionEntry): void
    {
        $this->adjustStock($productionEntry, $productionEntry->quantity);
    }
    public function updated(ProductionEntry $productionEntry): void
    {
        if (! $productionEntry->wasChanged('quantity')) {
            return;
        }

        $originalQuantity = $productionEntry->getOriginal('quantity');
        $difference = $productionEntry->quantity - $originalQuantity;

        $this->adjustStock($productionEntry, $difference);
    }

    public function deleted(ProductionEntry $productionEntry): void
    {
        $this->adjustStock($productionEntry, -1 * $productionEntry->quantity);
    }

    protected function adjustStock(ProductionEntry $productionEntry, int $quantityChange): void
    {
        if ($quantityChange === 0) {
            return;
        }

        $warehouseId = $productionEntry->production->warehouse_id;

        $stock = Stock::firstOrNew([
            'product_id' => $productionEntry->product_id,
            'warehouse_id' => $warehouseId,
        ]);

        $stock->quantity = ($stock->quantity ?? 0) + $quantityChange;
        $stock->save();
    }
}
