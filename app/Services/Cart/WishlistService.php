<?php

namespace App\Services\Cart;

use App\Models\Product;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Support\Facades\Auth;

class WishlistService
{
    public static function store(Product $product) {
        if ($rowId = self::existInWishlist($product->id)) {
            self::delete($rowId);
        } else {
            Cart::instance('wishlist')->add(
                $product->id,
                $product->name,
                1,
                $product->getPriceFinal()
            )->associate(Product::class);
            self::saveSession();
            session()->flash('alert', $product->name.' '.__('it was added correctly'));
            session()->flash('alert-type', 'success');
        }
    }
    public static function storeInCart(Product $product) {
        CartService::add($product, 1, $product->getPriceFinal());
    }
    public static function delete($rowId) {
        Cart::instance('wishlist')->remove($rowId);
        self::saveSession();
    }
    public static function existInWishlist($productId) {
        $existInWishlist = false;
        $cartItem = Cart::instance('wishlist')->search(function ($cartItem) use ($productId) {
            return $cartItem->id === $productId;
        });
        if ($cartItem->isNotEmpty()) {
            $existInWishlist = $cartItem->first()->rowId;
        }

        return $existInWishlist;
    }
    private static function saveSession() {
        if (Auth::check()) {
            Cart::instance('wishlist')->store(Auth::id());
        }
    }
}
