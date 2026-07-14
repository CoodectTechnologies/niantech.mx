<?php

namespace App\Livewire\Admin\Catalog\Brand;

use App\Models\ProductBrand;
use Exception;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $perPage = 50;
    public $search;
    protected $queryString = ['search'];
    protected $paginationTheme = 'bootstrap';
    protected $listeners = ['render'];

    public function updatingSearch() {
        $this->resetPage();
    }
    public function render() {
        $brands = ProductBrand::query()->with('products')->orderBy('id', 'desc');
        if ($this->search) {
            $brands = $brands->where('name', 'LIKE', "%{$this->search}%");
        }
        $brands = $brands->paginate($this->perPage);

        return view('livewire.admin.catalog.brand.index', compact('brands'));
    }
    public function destroy(ProductBrand $brand) {
        try {
            if ($brand->image) {
                if (Storage::exists($brand->image->url)) {
                    Storage::delete($brand->image->url);
                }
                $brand->image()->delete();
            }
            $brand->delete();
            $this->dispatch('alert', 'success', __('Successful elimination'));
        } catch (Exception $e) {
            report($e);
            $this->dispatch('alert', 'error', $e->getMessage());
        }
    }
}
