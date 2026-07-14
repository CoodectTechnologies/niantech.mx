<?php

namespace App\Livewire\Ecommerce\Wishlist;

use App\Models\Product;
use App\Services\Cart\WishlistService;
use Exception;
use Gloudemans\Shoppingcart\Facades\Cart;
use Livewire\Component;

class Index extends Component
{
    public function render() {
        $wishlists = Cart::instance('wishlist')->content();

        return view('livewire.ecommerce.wishlist.index', compact('wishlists'));
    }
    public function delete($rowId) {
        try {
            WishlistService::delete($rowId);
            $this->dispatch('render')->to('ecommerce.layouts.wishlist');
            $this->dispatch('alert', 'success', __('Article was successfully removed'));
        } catch (Exception $e) {
            $this->dispatch('alert', 'warning', __($e->getMessage()));
        }
    }
    public function storeInCart($productId) {
        try {
            $product = Product::find($productId);
            WishlistService::storeInCart($product);
            $this->dispatch('render')->to('ecommerce.layouts.cart');
            $this->dispatch('notify-add-wishlist', $this->product->name, route('ecommerce.product.show', $this->product), $this->product->imagePreview());
        } catch (Exception $e) {
            $this->dispatch('alert', 'warning', __($e->getMessage()));
        }
    }
}
