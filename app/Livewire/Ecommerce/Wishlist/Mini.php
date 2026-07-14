<?php

namespace App\Livewire\Ecommerce\Wishlist;

use App\Models\Product;
use App\Services\Cart\WishlistService;
use Exception;
use Livewire\Component;

class Mini extends Component
{
    public $product;
    public $isFavorite;

    public function mount(Product $product) {
        $this->product = $product;
    }
    public function render() {
        $this->isFavorite();

        return view('livewire.ecommerce.wishlist.mini');
    }
    public function isFavorite() {
        $this->isFavorite = WishlistService::existInWishlist($this->product->id);
    }
    public function store() {
        try {
            WishlistService::store($this->product);
            $this->dispatch('render')->to('ecommerce.layouts.wishlist');
            $this->dispatch('notify-add-wishlist', $this->product->name, route('ecommerce.wishlist.index'), $this->product->imagePreview());
        } catch (Exception $e) {
            $this->dispatch('alert', 'warning', __($e->getMessage()));
        }
    }
}
