<?php

namespace App\Console\Commands\Admin\Catalog;

use App\Models\Product;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class ProductToonExport extends Command
{
    protected $signature = 'catalog:product-toon-export';
    protected $description = 'Exporta productos publicados en formato TOON';

    public function handle() {
        $products = Product::where('status', Product::STATUS_PUBLISHED)->get(['name', 'detail', 'price', 'sku']);
        $count = $products->count();
        $header = "products[{$count}]{name,detail,price,sku}:";
        $lines = [];
        foreach ($products as $product) {
            $lines[] = sprintf(
                '%s,%s,%s,%s',
                str_replace(["\n", "\r", ','], [' ', ' ', ' '], $product->name),
                str_replace(["\n", "\r", ','], [' ', ' ', ' '], $product->detail),
                $product->price,
                $product->sku
            );
        }
        $toonContent = $header."\n".implode("\n", $lines);
        $path = 'ai/embebing/products/products.toon';
        Storage::put($path, $toonContent);
        $this->info('Archivo TOON generado en '.storage_path('app/'.$path));
    }
}
