<?php

namespace App\Livewire\Admin\Catalog\Category;

use App\Models\ProductCategory;
use Livewire\Component;

class Sortlist extends Component
{
    public $categories;
    public $categoriesToUpdate = [];

    public function mount() {
        $this->categories = json_decode(ProductCategory::getCache(), true);
    }
    public function render() {
        return view('livewire.admin.catalog.category.sortlist');
    }
    public function updateOrder($data) {
        foreach ($data as $item) {
            ProductCategory::query()
                ->where('id', $item['id'])
                ->update([
                    'parent_id' => $item['parent_id'],
                    'order' => $item['order'],
                ]);
            foreach ($item['sibling_ids'] as $order => $id) {
                ProductCategory::where('id', $id)->update(['order' => $order]);
            }
        }
        ProductCategory::regenerateCache();
        $this->dispatch('alert', 'success', 'Ordenamiento actualizado');
    }
}
