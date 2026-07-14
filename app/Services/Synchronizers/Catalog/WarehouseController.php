<?php

namespace App\Services\Synchronizers\Catalog;

use App\Http\Controllers\Controller;
use App\Models\ProductWarehouse;

class WarehouseController extends Controller
{
    public static function save($warehouses) {
        $syncWarehouses = activity()->withoutLogs(function () use ($warehouses) {
            $syncWarehouses = [];
            foreach ($warehouses as $warehouseArray) {
                $productWarehouse = ProductWarehouse::query()->where('name', $warehouseArray['name'])->first();
                if (! $productWarehouse) {
                    $productWarehouse = ProductWarehouse::create([
                        'name' => $warehouseArray['name'],
                        'provider' => $warehouseArray['provider'],
                        'provider_id' => $warehouseArray['provider_id'],
                    ]);
                }
                $syncWarehouses[$productWarehouse->id] = ['quantity' => intval($warehouseArray['quantity'])];
            }

            return $syncWarehouses;
        });

        return $syncWarehouses;
    }
}
