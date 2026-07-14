<?php

namespace App\Livewire\Admin\Catalog\Product\Product;

use App\Exports\Admin\Product\ProductExport;
use App\Imports\Admin\Product\ProductImport;
use App\Imports\Admin\Product\ProductWordpressImport;
use App\Models\Product;
use App\Models\ProductCategory;
use Exception;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;

class Index extends Component
{
    use WithFileUploads;
    use WithPagination;

    public $perPage = 30;
    public $search;
    public $statusFilter;
    public $stockFilter;
    public $categoryIdFilter;
    public $excelImportTmp;
    public $excelWordpressImportTmp;
    public $fileTmpInputId;
    public $fileWordpressTmpInputId;
    protected $queryString = ['search'];
    protected $paginationTheme = 'bootstrap';
    protected $listeners = ['render'];

    public function mount() {
        $this->loadRandomFileTmpInputId();
        $this->loadRandomFileWordpressTmpInputId();
    }
    public function updatingSearch() {
        $this->resetPage();
    }
    public function render() {
        $categories = ProductCategory::orderBy('name')->get();
        $products = Product::query()->with(['comments', 'image', 'images', 'productCategories', 'currency'])->orderBy('id', 'desc');
        if ($this->search) {
            $products = $products->where(function ($query) {
                $query->orWhere('name', 'LIKE', "%{$this->search}%")
                    ->orWhere('sku', 'LIKE', "%{$this->search}%")
                    ->orWhere('detail', 'LIKE', "%{$this->search}%")
                    ->orWhere('search_advanced', 'LIKE', "%{$this->search}%");
            });
        }
        if ($this->statusFilter) {
            $products = $products->where('status', $this->statusFilter);
        }
        if ($this->stockFilter) {
            $products = $products->whereHas('productWarehouses', function ($query) {
                $query->where('product_product_warehouse.quantity', '<=', Product::STOCK_LOW);
            });
        }
        if ($this->categoryIdFilter) {
            $products = $products->whereRelation('productCategories', function ($query) {
                $query->whereIn('product_category_id', [$this->categoryIdFilter]);
            });
        }
        $products = $products->paginate($this->perPage);

        return view('livewire.admin.catalog.product.product.index', compact('products', 'categories'));
    }
    public function destroy(Product $product) {
        try {
            if ($product->image) {
                if (Storage::exists($product->image->url)) {
                    Storage::delete($product->image->url);
                }
                $product->image()->delete();
            }
            if (count($product->images)) {
                foreach ($product->images as $img) {
                    $img->delete();
                }
            }
            if (count($product->comments)) {
                $product->comments()->delete();
            }
            $product->delete();
            $this->dispatch('alert', 'success', __('Successful elimination'));
        } catch (Exception $e) {
            report($e);
            $this->dispatch('alert', 'error', $e->getMessage());
        }
    }
    public function exportProducts() {
        $name = 'products-'.date('Y-m').'.xlsx';

        return Excel::download(new ProductExport, $name);
    }
    public function importProducts() {
        $this->validate(['excelImportTmp' => 'required']);
        try {
            Excel::import(new ProductImport, $this->excelImportTmp);
            $this->loadRandomFileTmpInputId();
            $this->reset('excelImportTmp');
            $this->dispatch('alert', 'success', 'Productos creados con éxito');
            $this->dispatch('render');
        } catch (Exception $e) {
            report($e);
            $this->dispatch('alert', 'error', $e->getMessage());
        }
    }
    public function importWordpressProducts() {
        $this->validate(['excelWordpressImportTmp' => 'required']);
        try {
            Excel::import(new ProductWordpressImport, $this->excelWordpressImportTmp);
            $this->loadRandomFileWordpressTmpInputId();
            $this->reset('excelWordpressImportTmp');
            $this->dispatch('alert', 'success', 'Productos creados con éxito');
            $this->dispatch('render');
        } catch (Exception $e) {
            report($e);
            $this->dispatch('alert', 'error', $e->getMessage());
        }
    }
    protected function loadRandomFileTmpInputId() {
        $this->fileTmpInputId = rand(1, 1000);
    }
    protected function loadRandomFileWordpressTmpInputId() {
        $this->fileWordpressTmpInputId = rand(1, 1000);
    }
}
