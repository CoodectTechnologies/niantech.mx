<?php

namespace App\Livewire\Ecommerce\Layouts;

use App\Models\ProductCategory;
use Livewire\Component;

class MenuCategoryMega extends Component
{
    public function render() {
        $categories = ProductCategory::getCache()->where('includeInMenu', true)->where('productsCount', '>', 0);
        $categories = json_decode(json_encode($categories), true);
        $categories = array_chunk($categories, 12, true);

        return view('livewire.ecommerce.layouts.menu-category-mega', compact('categories'));
    }
}
