<?php

namespace App\Livewire\Ecommerce\Category;

use App\Models\ProductCategory;
use Livewire\Component;

class CategoryList extends Component
{
    public function render() {
        $categoriesFhater = ProductCategory::getCache()->take(6);

        return view('livewire.ecommerce.category.category-list', compact('categoriesFhater'));
    }
}
