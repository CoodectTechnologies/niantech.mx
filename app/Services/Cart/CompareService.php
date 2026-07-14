<?php

namespace App\Services\Cart;

use App\Models\Product;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Support\Facades\Auth;

class CompareService
{
    public static function store(Product $product) {
        if (! self::existInCompare($product->id)) {
            Cart::instance('compare')->add([
                'id' => $product->id,
                'name' => $product->name,
                'qty' => 1,
                'price' => $product->getPriceFinal(),
            ])->associate(Product::class);
            self::saveSession();
            session()->flash('alert', $product->name.' '.__('added'));
            session()->flash('alert-type', 'success');
        }
    }
    public static function storeInCart(Product $product) {
        CartService::add($product, 1, $product->getPriceFinal());
    }
    public static function delete($rowId) {
        Cart::instance('compare')->remove($rowId);
        self::saveSession();
    }
    private static function existInCompare($productId) {
        $existInCompare = false;
        $cartItem = Cart::instance('compare')->search(function ($cartItem) use ($productId) {
            return $cartItem->id === $productId;
        });
        if ($cartItem->isNotEmpty()) {
            $existInCompare = true;
        }

        return $existInCompare;
    }
    private static function saveSession() {
        if (Auth::check()) {
            Cart::instance('compare')->store(Auth::id());
        }
    }
}
