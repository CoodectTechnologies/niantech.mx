<?php

namespace App\Livewire\Ecommerce\Layouts;

use App\Models\ProductCategory;
use Livewire\Component;

class MenuMobileCategory extends Component
{
    public function render() {
        $categories = ProductCategory::getCache()->where('includeInMenu', true)->where('productsCount', '>', 0);

        return view('livewire.ecommerce.layouts.menu-mobile-category', compact('categories'));
    }
}
