<?php

namespace App\Livewire\Ecommerce\Category;

use App\Models\ProductCategory;
use Livewire\Component;

class Index extends Component
{
    public function render() {
        $categoriesFather = ProductCategory::getCache();

        return view('livewire.ecommerce.category.index', compact('categoriesFather'));
    }
}
