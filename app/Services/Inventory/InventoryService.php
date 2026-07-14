<?php

namespace App\Services\Inventory;

use App\Models\OrderProduct;
use App\Models\OrderProductWarehouse;
use App\Models\Product;
use App\Models\ProductVariant;

class InventoryService
{
    public function createOrderProductWarehouses(OrderProduct $orderProduct, Product $product, ?int $variantId = null): void {
        $productWarehouses = $this->getAvailableWarehouses($product, $variantId);
        $requiredQuantity = $orderProduct->quantity;

        foreach ($productWarehouses as $productWarehouse) {
            $availableQuantity = $productWarehouse->pivot->quantity;
            $quantityToDeduct = min($requiredQuantity, $availableQuantity);
            if ($quantityToDeduct <= 0) {
                continue;
            }

            OrderProductWarehouse::create([
                'order_product_id' => $orderProduct->id,
                'product_warehouse_id' => $productWarehouse->id,
                'quantity' => $quantityToDeduct,
                'apply_provider' => (bool) $productWarehouse->provider,
                'provider' => $productWarehouse->provider,
            ]);
            $requiredQuantity -= $quantityToDeduct;
            if ($requiredQuantity <= 0) {
                break;
            }
        }
    }
    protected function getAvailableWarehouses(Product $product, ?int $variantId = null) {
        if ($variantId) {
            $variant = ProductVariant::find($variantId);
            if (! $variant) {
                return collect();
            }

            return $variant
                ->productWarehouses()
                ->wherePivot('quantity', '>', 0)
                ->get();
        }

        return $product
            ->productWarehouses()
            ->where('quantity', '>', 0)
            ->get();
    }
    public function getAvailableQuantity(Product $product, ?int $variantId = null): int {
        if ($variantId) {
            $variant = ProductVariant::find($variantId);

            return $variant?->getQuantityTotal() ?? 0;
        }

        return $product->getQuantityTotal();
    }
}
