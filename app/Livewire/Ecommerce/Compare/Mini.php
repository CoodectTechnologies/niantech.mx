<?php

namespace App\Livewire\Ecommerce\Compare;

use App\Models\Product;
use App\Services\Cart\CompareService;
use Exception;
use Livewire\Component;

class Mini extends Component
{
    public $product;

    public function mount(Product $product) {
        $this->product = $product;
    }
    public function render() {
        return view('livewire.ecommerce.compare.mini');
    }
    public function store() {
        try {
            CompareService::store($this->product);
            $this->dispatch('render')->to('ecommerce.layouts.compare');
            $this->dispatch('notify-add-compare', $this->product->name, route('ecommerce.compare.index'), $this->product->imagePreview());
        } catch (Exception $e) {
            $this->dispatch('alert', 'warning', __($e->getMessage()));
        }
    }
}
