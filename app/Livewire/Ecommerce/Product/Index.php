<?php

namespace App\Livewire\Ecommerce\Product;

use App\Models\AnalyticSearch;
use App\Models\Banner;
use App\Models\Product;
use App\Models\ProductBrand;
use App\Models\ProductCategory;
use App\Models\ProductGender;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Livewire\Component;
use Livewire\WithPagination;
use Stevebauman\Location\Facades\Location;

class Index extends Component
{
    use WithPagination;

    public $perPage = 50;
    protected $paginationTheme = 'bootstrap';

    // Tools
    public $category;
    public $bannersSidebar = [];

    // Filters
    public $search;
    public $searchCustom;
    public $orderBy;
    public $brand;
    public $gender;
    public $minPrice;
    public $maxPrice;
    public $type;
    public $promotions;
    public $featured;
    public $new;
    public $categories = [];
    public $brands = [];
    protected $queryString = [
        'search',
        'searchCustom',
        'orderBy',
        'brand',
        'gender',
        'minPrice',
        'maxPrice',
        'type',
        'promotions',
        'featured',
        'new',
    ];

    public function mount(Request $request, $category = null) {
        $this->category = $category;
        $this->loadRequestFilter($request);
        $this->loadBanners($request);
    }
    public function render() {
        $productCategories = $this->getCategories();
        $productGenders = $this->getGenders();
        $productBrands = $this->getBrands();
        $products = $this->getProducts();

        return view('livewire.ecommerce.product.index', compact('products', 'productCategories', 'productBrands', 'productGenders'));
    }
    private function getProducts() {
        $products = Product::withRelations()->validateProduct();
        $products = $this->filters($products);
        $products = $products->paginate($this->perPage);

        return $products;
    }
    private function getCategories() {
        $productCategories = collect();
        $brands = $this->brands ?: ($this->brand ? [$this->brand] : []);
        if ($this->category) {
            $rootCategory = ProductCategory::getCache($this->category->id);
            if (! isset($rootCategory->childrens)) {
                return $productCategories;
            }
            $productCategories = $rootCategory->childrens;
            if (count($brands)) {
                $productCategories = $rootCategory->childrens->filter(function ($child) use ($brands) {
                    $childCategory = ProductCategory::query()
                        ->where('slug', $child->slug)
                        ->whereHas('products', function ($query) use ($brands) {
                            $query->whereIn('product_brand_id', $brands);
                        })->first();

                    return $childCategory ? true : false;
                });
            }
        } else {
            $productCategories = ProductCategory::getCache();
        }

        return $productCategories;
    }
    private function getBrands() {
        $categories = $this->categories ?: ($this->category ? [$this->category->slug] : []);
        if (count($categories)) {
            $productBrands = ProductBrand::query()
                ->orderBy('name')
                ->has('products')
                ->whereHas('products', function ($query) use ($categories) {
                    $query->whereHas('productCategories', function ($query) use ($categories) {
                        $query->whereIn('slug', $categories);
                    })->validateProduct();
                })->with(['products' => function ($query) use ($categories) {
                    $query->whereHas('productCategories', function ($query) use ($categories) {
                        $query->whereIn('slug', $categories);
                    })->validateProduct();
                }])
                ->get();
        } else {
            $productBrands = ProductBrand::orderBy('name')->has('products')->with('products')->get();
        }

        return $productBrands;
    }
    private function getGenders() {
        $productGenders = ProductGender::with('products')->get();

        return $productGenders;
    }
    public function filters($products) {
        if (count($this->categories)) {
            $allChildrenIds = [];
            $categories = ProductCategory::validateCategory()->whereIn('slug', $this->categories)->get();
            foreach ($categories as $category) {
                array_push($allChildrenIds, ProductCategory::getCacheAllChildrenIds($category->id));
            }
            $allChildrenIds = call_user_func_array('array_merge', $allChildrenIds);
            $allChildrenIds = array_unique($allChildrenIds);
            $products = $products->whereHas('productCategories', function ($query) use ($allChildrenIds) {
                $query->whereIn('product_category_id', $allChildrenIds);
            });
        } else {
            if ($this->category) {
                $allChildrenIds = ProductCategory::getCacheAllChildrenIds($this->category->id);
                $products = $products->whereHas('productCategories', function ($query) use ($allChildrenIds) {
                    $query->whereIn('product_category_id', $allChildrenIds);
                });
            }
        }
        if (count($this->brands)) {
            $products = $products->whereIn('product_brand_id', $this->brands);
        } else {
            if ($this->brand) {
                $products = $products->whereRelation('productBrand', 'slug', $this->brand);
            }
        }
        if ($this->gender) {
            $products = $products->whereRelation('productGenders', 'slug', $this->gender);
        }

        $useCurrencyJoin = $this->orderBy === 'price-low' || $this->orderBy === 'price-high' || $this->minPrice !== null || $this->maxPrice !== null;
        if ($useCurrencyJoin) {
            $products = $products->withConvertedPrice();
        }

        if ($this->orderBy) {
            if ($this->orderBy == 'featured') {
                $products = $products->where('featured', true);
            }
            if ($this->orderBy == 'name-asc') {
                $products = $products->orderBy('name');
            }
            if ($this->orderBy == 'name-desc') {
                $products = $products->orderByDesc('name');
            }
            if ($this->orderBy == 'price-low') {
                $products = $products->orderByConvertedPrice('asc');
            }
            if ($this->orderBy == 'price-high') {
                $products = $products->orderByConvertedPrice('desc');
            }
        } else {
            $products = $products->orderBy('id', 'desc');
        }
        if ($this->minPrice !== null || $this->maxPrice !== null) {
            $products = $products->filterByConvertedPrice($this->minPrice, $this->maxPrice);
        }
        if ($this->type) {
            $products = $products->where('type', $this->type);
        }
        if ($this->promotions) {
            $products = $products->hasPromotions();
        }
        if ($this->featured) {
            $products = $products->where('featured', true);
        }
        if ($this->new) {
            $date = Carbon::parse(today())->subDays(Product::DAYS_IS_NEW);
            $products = $products->whereDate('created_at', '>=', $date);
        }
        if ($this->search || $this->searchCustom) {
            $search = $this->searchCustom ?? $this->search;
            if ($this->searchCustom) {
                $this->search = null;
            }
            $products = $products->where(function ($query) use ($search) {
                $query->orWhere('name', 'LIKE', "%{$search}%")
                    ->orWhere('sku', 'LIKE', "%{$search}%")
                    ->orWhere('detail', 'LIKE', "%{$search}%")
                    ->orWhere('search_advanced', 'LIKE', "%{$search}%");
            });
            if ($this->search) {
                $products = $products->orWhere(function ($query) {
                    $query->whereRelation('productCategories', 'name', 'LIKE', "%{$this->search}%")
                        ->whereRelation('productGenders', 'name', 'LIKE', "%{$this->search}%")
                        ->whereRelation('productBrand', 'name', 'LIKE', "%{$this->search}%");
                });
                $this->saveAnalyticSearch($products, $this->search);
            }
        }

        return $products;
    }
    public function filterPrice($minPrice = null, $maxPrice = null) {
        $this->minPrice = $minPrice !== null && $minPrice !== '' ? (float) $minPrice : null;
        $this->maxPrice = $maxPrice !== null && $maxPrice !== '' ? (float) $maxPrice : null;
    }
    public function existAnyFilter() {
        $existAnyFilter = false;
        if (
            $this->search ||
            $this->searchCustom ||
            count($this->categories) ||
            $this->brand ||
            count($this->brands) ||
            $this->orderBy ||
            $this->minPrice !== null ||
            $this->maxPrice !== null
        ) {
            $existAnyFilter = true;
        }

        return $existAnyFilter;
    }
    public function clearFilter() {
        $this->reset('search', 'searchCustom', 'categories', 'brand', 'brands', 'orderBy', 'minPrice', 'maxPrice');
    }
    private function saveAnalyticSearch($properties, $search) {
        $founded = false;
        $search = htmlspecialchars(addslashes($search));
        if ($properties->count()) {
            $founded = true;
        }
        $data = Location::get(request()->ip());
        AnalyticSearch::create([
            'search' => $search,
            'founded' => $founded,
            'data' => json_encode($data),
        ]);
    }
    private function loadRequestFilter($request) {
        if ($request->search) {
            $this->search = $request->search;
        }
        if ($request->orderBy) {
            $this->orderBy = $request->orderBy;
        }
        if ($request->brand) {
            $this->brand = $request->brand;
        }
        if ($request->gender) {
            $this->gender = $request->gender;
        }
        if ($request->minPrice !== null && $request->minPrice !== '') {
            $this->minPrice = (float) $request->minPrice;
        }
        if ($request->maxPrice !== null && $request->maxPrice !== '') {
            $this->maxPrice = (float) $request->maxPrice;
        }
        if ($request->type) {
            $this->type = $request->type;
        }
        if ($request->promotions) {
            $this->promotions = $request->promotions;
        }
        if ($request->featured) {
            $this->featured = $request->featured;
        }
        if ($request->new) {
            $this->new = $request->new;
        }
    }
    private function loadBanners() {
        $this->bannersSidebar = Banner::whereRelation('moduleWeb', 'name', 'Productos sidebar')->get();
    }
    public function updatingCategories($categories) {
        $this->resetPage();
    }
}
