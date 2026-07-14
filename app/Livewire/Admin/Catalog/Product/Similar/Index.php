<?php

namespace App\Livewire\Admin\Catalog\Product\Similar;

use App\Models\Product;
use App\Models\ProductSimilar;
use Exception;
use Livewire\Component;

class Index extends Component
{
    protected $listeners = ['render'];
    public $product;

    public function mount(Product $product) {
        $this->product = $product;
        $this->product->load('productSimilars.product.image');
    }
    public function render() {
        $similars = $this->product->productSimilars;

        return view('livewire.admin.catalog.product.similar.index', compact('similars'));
    }
    public function destroy(ProductSimilar $productSimilar) {
        try {
            $productSimilar->delete();
            $this->dispatch('alert', 'success', __('Successful elimination'));
        } catch (Exception $e) {
            report($e);
            $this->dispatch('alert', 'error', $e->getMessage());
        }
    }
}
