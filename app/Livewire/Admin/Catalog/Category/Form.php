<?php

namespace App\Livewire\Admin\Catalog\Category;

use App\Models\Product;
use App\Models\ProductCategory;
use App\Traits\LivewireTranslatable;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class Form extends Component
{
    use LivewireTranslatable;
    use WithFileUploads;
    use WithPagination;

    protected $paginationTheme = 'bootstrap';
    public $category;
    public $method;

    // Tools
    public $imageTmp;
    public $bannerTmp;
    public $imageIconTmp;
    public $categories;
    public $categoryParentArray = [];
    public $productsInCategoryTmp = [];

    // Filters
    public $search;

    protected function rules() {
        return [
            'category.status' => 'nullable',
            'category.parent_id' => 'nullable',
            'category.key_product_or_service' => 'nullable',
            'translations.name.'.translatable() => 'required',
            'translations.description.'.translatable() => 'nullable',
            'category.include_in_menu' => 'nullable',
            'imageTmp' => 'image|nullable',
            'bannerTmp' => 'image|nullable',
            'imageIconTmp' => 'image|nullable',
            'categoryParentArray' => 'nullable|array|max:1',
        ];
    }
    public function mount(ProductCategory $category, $method) {
        $this->category = $category;
        $this->method = $method;
        $this->categoryParentArray = [$category->parent_id];
        $this->category->status = $this->category->exists ? $this->category->status : true;
        $this->category->include_in_menu = $this->category->exists ? $this->category->include_in_menu : true;
        if ($this->category->exists) {
            $this->category->load('products', 'image', 'banner', 'imageIcon');
        }
        $this->categories = json_decode(ProductCategory::getCache(), true);
        $this->loadTranslations($this->category);
    }
    public function render() {
        $products = $this->getProducts();

        return view('livewire.admin.catalog.category.form', compact('products'));
    }
    public function store() {
        $this->validate();
        $this->saveCategoryParent();
        $this->saveTranslations($this->category);
        $this->category->save();
        $this->saveImage();
        $this->saveBanner();
        $this->saveImageIcon();
        $this->saveProducts();
        $this->category = new ProductCategory;
        $this->reset('imageTmp', 'bannerTmp', 'imageIconTmp', 'categoryParentArray');
        ProductCategory::regenerateCache();
        $this->dispatch('alert', 'success', __('Registration successfully added'));
        $this->dispatch('render');
    }
    public function update() {
        $this->validate();
        $this->saveCategoryParent();
        $this->saveTranslations($this->category);
        $this->category->update();
        $this->saveImage();
        $this->saveBanner();
        $this->saveImageIcon();
        $this->saveProducts();
        ProductCategory::regenerateCache();
        $this->dispatch('alert', 'success', __('Registration successfully updated'));
        $this->dispatch('render');
    }
    public function saveImage() {
        if ($this->imageTmp) {
            $url = $this->imageTmp->store('catalog/category');
            imageManager($url, 300, $this->category);
        }
    }
    public function saveBanner() {
        if ($this->bannerTmp) {
            $url = $this->bannerTmp->store('catalog/category');
            imageManager($url, 1920, $this->category, 'banner');
        }
    }
    public function saveImageIcon() {
        if ($this->imageIconTmp) {
            $url = $this->imageIconTmp->store('catalog/category');
            imageManager($url, 200, $this->category, 'imageIcon');
        }
    }
    public function saveProducts() {
        foreach ($this->productsInCategoryTmp as $productIdInCategory => $productInCategory) {
            $this->category->products()->attach($productIdInCategory);
        }
        $this->productsInCategoryTmp = [];
    }
    private function getProducts() {
        $products = Product::withRelations()->validateProduct();
        if ($this->search) {
            $products = $products->where('name', 'LIKE', "%{$this->search}%")
                ->orWhere('sku', 'LIKE', "%{$this->search}%")
                ->orWhere('detail', 'LIKE', "%{$this->search}%")
                ->orWhere('search_advanced', 'LIKE', "%{$this->search}%")
                ->orWhereRelation('productCategories', 'name', 'LIKE', "%{$this->search}%")
                ->orWhereRelation('productGenders', 'name', 'LIKE', "%{$this->search}%")
                ->orWhereRelation('productBrand', 'name', 'LIKE', "%{$this->search}%");
        }
        $products = $products->paginate();

        return $products;
    }
    public function addProduct($productId) {
        $product = Product::with('image')->where('id', $productId)->first();
        if ($product) {
            $this->productsInCategoryTmp[$product->id] = [
                'name' => $product->name,
                'image' => $product->imagePreview(),
                'slug' => $product->slug,
                'sku' => $product->sku,
                'type' => $product->type,
            ];
        }
    }
    private function saveCategoryParent() {
        if (isset($this->categoryParentArray[0])) { // Si se eligio una categoria padre
            if ($this->categoryParentArray[0]) { // Si no es 0, ya que 0 es "Sin categoría padre"
                $parentId = $this->categoryParentArray[0];
            } else {
                $parentId = null;
            }
        } else { // No eligio una categoria padre
            if ($this->category->parent_id) { // Se pondrá el parent_id que ya se tiene registrado
                $parentId = $this->category->parent_id;
            } else {
                $parentId = null;
            }
        }
        $this->category->parent_id = $parentId;
    }
    public function removeImage() {
        if ($this->category->image) {
            if (Storage::exists($this->category->image->url)) {
                Storage::delete($this->category->image->url);
            }
            $this->category->image()->delete();
            $this->category->image = null;
        }
        $this->reset('imageTmp');
        $this->dispatch('alert', 'success', __('Image successfully deleted'));
    }
    public function removeBanner() {
        if ($this->category->banner) {
            if (Storage::exists($this->category->banner->url)) {
                Storage::delete($this->category->banner->url);
            }
            $this->category->banner()->delete();
            $this->category->banner = null;
        }
        $this->reset('bannerTmp');
        $this->dispatch('alert', 'success', __('Image successfully deleted'));
    }
    public function removeImageIcon() {
        if ($this->category->imageIcon) {
            if (Storage::exists($this->category->imageIcon->url)) {
                Storage::delete($this->category->imageIcon->url);
            }
            $this->category->imageIcon()->delete();
            $this->category->imageIcon = null;
        }
        $this->reset('imageIconTmp');
        $this->dispatch('alert', 'success', __('Image successfully deleted'));
    }
    public function deleteInCategory($productId) {
        $this->category->products()->detach([$productId]);
        $this->category->load('products');
        $this->dispatch('alert', 'success', __('Product removed from category'));
    }
    public function deleteInCategoryTmp($productId) {
        unset($this->productsInCategoryTmp[$productId]);
        $this->dispatch('alert', 'success', __('Product removed from category'));
    }
}
