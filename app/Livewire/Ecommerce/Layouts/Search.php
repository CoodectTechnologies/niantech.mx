<?php

namespace App\Livewire\Ecommerce\Layouts;

use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Http\Request;
use Livewire\Component;

class Search extends Component
{
    public $search;
    public $minCharacters = 3;

    public function mount(Request $request) {
        $this->search = $request->search;
    }
    public function render() {
        $products = $this->getProducts();
        $categories = $this->getCategories();

        return view('livewire.ecommerce.layouts.search', compact('products', 'categories'));
    }
    private function getProducts() {
        $products = [];
        if ($this->search && strlen($this->search) >= $this->minCharacters) {
            $products = Product::query()
                ->validateProduct()
                ->where(function ($query) {
                    $query->where('name', 'LIKE', "%{$this->search}%")
                        ->orWhere('sku', 'LIKE', "%{$this->search}%")
                        ->orWhere('detail', 'LIKE', "%{$this->search}%")
                        ->orWhere('search_advanced', 'LIKE', "%{$this->search}%")
                        ->validateProduct();
                })
                ->take(6)
                ->get();
        }

        return $products;
    }
    private function getCategories() {
        $categories = [];
        if ($this->search && strlen($this->search) >= $this->minCharacters) {
            $categories = ProductCategory::validateCategory()->where('name', 'LIKE', "%{$this->search}%")->take(6)->get();
        }

        return $categories;
    }
}
