<?php

namespace App\Livewire\Admin\Catalog\Category;

use App\Models\ProductCategory;
use Exception;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    protected $queryString = ['search'];
    protected $paginationTheme = 'bootstrap';
    protected $listeners = ['render'];

    // Models
    public $categoriesFather = [];

    // Tools
    public $perPage = 20;
    public $search;

    // Filters
    public $categoryFhaterFilter;
    public $statusFilter;
    public $includeInMenuFilter;
    public $onlyParentsFilter;

    public function mount() {
        $this->loadCategoriesFhater();
    }
    public function render() {
        $categories = $this->getCategories();

        return view('livewire.admin.catalog.category.index', compact('categories'));
    }
    private function getCategories() {
        $categories = ProductCategory::query()->with(['products', 'allChildrens'])->orderBy('order');
        $categories = $this->filters($categories);
        $categories = $categories->paginate($this->perPage);

        return $categories;
    }
    private function filters($categories) {
        if ($this->search) {
            $categories = $categories->where('name', 'LIKE', "%{$this->search}%");
        }
        if ($this->categoryFhaterFilter) {
            $categoryFather = ProductCategory::find($this->categoryFhaterFilter);
            $categories = $categories->allChildrens($categoryFather);
        }
        if ($this->statusFilter == 1) {
            $categories = $categories->where('status', true);
        } elseif ($this->statusFilter == 2) {
            $categories = $categories->where('status', false);
        }
        if ($this->includeInMenuFilter == 1) {
            $categories = $categories->where('include_in_menu', true);
        } elseif ($this->includeInMenuFilter == 2) {
            $categories = $categories->where('include_in_menu', false);
        }
        if ($this->onlyParentsFilter) {
            $categories = $categories->whereNull('parent_id');
        }

        return $categories;
    }
    private function loadCategoriesFhater() {
        $this->categoriesFather = ProductCategory::query()->whereNull('parent_id')->orderBy('id', 'desc')->get();
    }
    public function destroy(ProductCategory $category) {
        try {
            if ($category->image) {
                if (Storage::exists($category->image->url)) {
                    Storage::delete($category->image->url);
                }
                $category->image()->delete();
            }
            Cache::forget('productCategory-'.$category->id);
            ProductCategory::regenerateCache();
            $category->delete();
            $this->dispatch('alert', 'success', __('Successful elimination'));
        } catch (Exception $e) {
            report($e);
            $this->dispatch('alert', 'error', $e->getMessage());
        }
    }

    // UPDATES MAGIC
    public function updatingSearch() {
        $this->resetPage();
    }
}
