<?php

namespace App\Services\Synchronizers\Catalog;

use App\Http\Controllers\Controller;
use App\Models\ProductBrand;

class BrandController extends Controller
{
    public static function save($brand) {
        $brandId = activity()->withoutLogs(function () use ($brand) {
            $brandId = null;
            if ($brand) {
                $productBrand = ProductBrand::query()->where('name', $brand)->first();
                if (! $productBrand) {
                    $productBrand = ProductBrand::create(['name' => $brand]);
                }
                $brandId = $productBrand->id;
            }

            return $brandId;
        });

        return $brandId;
    }
}
