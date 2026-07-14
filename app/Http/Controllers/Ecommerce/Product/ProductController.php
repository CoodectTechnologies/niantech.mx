<?php

namespace App\Http\Controllers\Ecommerce\Product;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;

class ProductController extends Controller
{
    public function index(Request $request) {
        $category = null;
        if ($request->category) {
            $category = ProductCategory::where('slug', $request->category)->first() ?? null;
        }

        return view('ecommerce.product.index', compact('category'));
    }
    public function show($slug) {
        $product = Product::query()->withRelations()->where('slug', $slug)->first();
        if ($product->status == Product::STATUS_DRAFT) {
            return redirect()->back();
        }
        $this->addToViewRecent($product);

        return view('ecommerce.product.show', compact('product'));
    }
    private function addToViewRecent($product) {
        $minutesOfLifeCookie = 10080; // 1 Semana
        $productViewRecents = [];
        $limitProductsToSave = 10;

        if (Cookie::has(Product::COOKIE_PRODUCT_VIEW_RECENTS)) {
            $productViewRecents = json_decode(Cookie::get(Product::COOKIE_PRODUCT_VIEW_RECENTS), true);
            if (! in_array($product->id, $productViewRecents)) {
                if (count($productViewRecents) >= $limitProductsToSave) {
                    array_shift($productViewRecents);
                }
                $productViewRecents[] = $product->id;
            }
        } else {
            $productViewRecents[] = $product->id;
        }
        Cookie::queue(Product::COOKIE_PRODUCT_VIEW_RECENTS, json_encode($productViewRecents), $minutesOfLifeCookie);
        views($product)->cooldown(now()->addHours(1))->record();
    }
}
