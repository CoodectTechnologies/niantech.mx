<?php

namespace App\Livewire\Ecommerce\Cart;

use App\Models\Product;
use App\Models\ShippingZone;
use App\Services\Cart\CartService;
use App\Services\Shipping\ShippingService;
use Exception;
use Gloudemans\Shoppingcart\Facades\Cart;
use Livewire\Component;

class Index extends Component
{
    public function render() {
        $cart = Cart::instance('default')->content();
        $subtotal = Cart::subtotal();
        $tax = Cart::instance('default')->tax();
        $getShippingZonesFreeShippingOverTo = $this->getShippingZonesFreeShippingOverTo();

        return view('livewire.ecommerce.cart.index', compact('cart', 'subtotal', 'tax', 'getShippingZonesFreeShippingOverTo'));
    }
    public function update($productId, $rowId, $qty) {
        try {
            $product = Product::findOrFail($productId);
            CartService::update($product, $rowId, $qty);
            $this->dispatch('render')->to('ecommerce.layouts.cart');
            $this->dispatch('alert', 'success', $product->name.' '.__('added'));
        } catch (Exception $e) {
            $this->dispatch('alert', 'warning', __($e->getMessage()));
        }
    }
    public function delete($rowId) {
        CartService::destroy($rowId);
        $this->dispatch('render')->to('ecommerce.layouts.cart');
    }
    public function deleteCart() {
        Cart::instance('default')->destroy();
        $this->dispatch('render')->to('ecommerce.layouts.cart');
    }
    private function getShippingZonesFreeShippingOverTo() {
        $shippingZones = [];
        if ($this->loadShippingApplies()) {
            $shippingZones = ShippingZone::whereNotNull('free_shipping_over_to')->get();
        }

        return $shippingZones;
    }
    private function loadShippingApplies() {
        return (new ShippingService)->applyShipping('default');
    }
}
