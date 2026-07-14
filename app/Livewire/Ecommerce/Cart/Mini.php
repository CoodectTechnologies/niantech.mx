<?php

namespace App\Livewire\Ecommerce\Cart;

use App\Models\Product;
use App\Services\Cart\CartService;
use Exception;
use Illuminate\Support\Facades\Session;
use Livewire\Component;

class Mini extends Component
{
    public $product;

    public function mount(Product $product) {
        $this->product = $product;
    }
    public function render() {
        return view('livewire.ecommerce.cart.mini');
    }
    public function store() {
        $type = $this->product->getType();
        if ($this->product->getType() == Product::TYPE_PHYSICAL_AND_DIGITAL) {
            $type = Product::TYPE_PHYSICAL;
        }
        $options = [
            'image' => $this->product->imagePreview(),
            'price' => $this->product->getPriceFinal(),
            'type' => $type,
            'currency' => Session::get('currency'),
        ];
        try {
            CartService::add($this->product, 1, $this->product->getPriceFinal(), $options);
            $this->dispatch('render')->to('ecommerce.layouts.cart');
            $this->dispatch('notify-add-cart', $this->product->name, route('ecommerce.product.show', $this->product), $this->product->imagePreview());
        } catch (Exception $e) {
            $this->dispatch('alert', 'warning', __($e->getMessage()));
        }
    }
}
