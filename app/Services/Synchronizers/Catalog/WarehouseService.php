<?php

namespace App\Services\Synchronizers\Catalog;

use App\Models\ProductWarehouse;

class WarehouseService
{
    public static function save($warehouses) {
        $syncWarehouses = activity()->withoutLogs(function () use ($warehouses) {
            $syncWarehouses = [];
            foreach ($warehouses as $warehouseArray) {
                $productWarehouse = ProductWarehouse::query()->where('name', $warehouseArray['name'])->first();
                if (! $productWarehouse) {
                    $productWarehouse = ProductWarehouse::create([
                        'name' => $warehouseArray['name'],
                        'external' => $warehouseArray['external'],
                        'external_id' => $warehouseArray['external_id'],
                    ]);
                }
                $syncWarehouses[$productWarehouse->id] = ['quantity' => intval($warehouseArray['quantity'])];
            }

            return $syncWarehouses;
        });

        return $syncWarehouses;
    }
}
