<?php

namespace App\Livewire\Ecommerce\Compare;

use App\Models\Product;
use App\Services\Cart\CompareService;
use Exception;
use Gloudemans\Shoppingcart\Facades\Cart;
use Livewire\Component;

class Index extends Component
{
    public function render() {
        $compares = Cart::instance('compare')->content();

        return view('livewire.ecommerce.compare.index', compact('compares'));
    }
    public function delete($rowId) {
        try {
            CompareService::delete($rowId);
            $this->dispatch('render')->to('ecommerce.layouts.compare');
            $this->dispatch('alert', 'success', __('Article was successfully removed'));
        } catch (Exception $e) {
            $this->dispatch('alert', 'warning', __($e->getMessage()));
        }
    }
    public function storeInCart($productId) {
        try {
            $product = Product::find($productId);
            CompareService::storeInCart($product);
            $this->dispatch('render')->to('ecommerce.layouts.cart');
            $this->dispatch('notify-add-compare', $this->product->name, route('ecommerce.product.show', $this->product), $this->product->imagePreview());
        } catch (Exception $e) {
            $this->dispatch('alert', 'warning', __($e->getMessage()));
        }
    }
}
