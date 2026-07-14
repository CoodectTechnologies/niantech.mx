<?php

namespace App\Models;

use Carbon\Carbon;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Sitemap\Contracts\Sitemapable;
use Spatie\Sitemap\Tags\Url;
use Spatie\Translatable\HasTranslations;

class ProductCategory extends Model implements Sitemapable
{
    use HasFactory;
    use HasTranslations;
    use LogsActivity;
    use Sluggable;

    protected $guarded = [];
    public $translatable = ['name', 'description', 'meta_title', 'meta_description', 'meta_keywords'];

    public function getActivitylogOptions(): LogOptions {
        return LogOptions::defaults()
            ->useLogName('Producto categoría')
            ->setDescriptionForEvent(fn (string $eventName) => "Una categoría de producto ha sido  {$eventName}")
            ->dontSubmitEmptyLogs()
            ->logOnlyDirty()
            ->logAll();
    }
    public function toSitemapTag(): Url|string|array {
        return route('ecommerce.product.index', ['category' => $this]);
    }
    public function getRouteKeyName() {
        return 'slug';
    }
    public function sluggable(): array {
        return [
            'slug' => [
                'source' => 'name',
            ],
        ];
    }
    public function parent() {
        return $this->belongsTo(ProductCategory::class, 'parent_id');
    }
    public function childrens() {
        return $this->hasMany(ProductCategory::class, 'parent_id')->orderBy('name')->with(['image', 'banner', 'imageIcon'])->with(['products' => function ($query) {
            $query->validateProduct();
        }]);
    }
    public function allChildrens() {
        return $this->childrens()->with('allChildrens');
    }
    public function products() {
        return $this->belongsToMany(Product::class);
    }
    public function promotions() {
        return $this->morphToMany(Promotion::class, 'promotionable')->withTimestamps();
    }
    public function coupons() {
        return $this->morphToMany(Coupon::class, 'couponable')->withTimestamps();
    }
    public function image() {
        return $this->morphOne(Image::class, 'imageable')->where('main', true)->where('name', null);
    }
    public function banner() {
        return $this->morphOne(Image::class, 'imageable')->where('main', true)->where('name', 'banner');
    }
    public function imageIcon() {
        return $this->morphOne(Image::class, 'imageable')->where('main', true)->where('name', 'imageIcon');
    }
    public function imagePreview() {
        $image = asset('assets/admin/media/product/default.png');
        if ($this->image) {
            if (Storage::exists($this->image->url)) {
                $image = Storage::url($this->image->url);
            } else {
                $image = $this->image->url;
            }
        }

        return $image;
    }
    public function bannerPreview() {
        $banner = asset('assets/admin/media/svg/files/blank-image.svg');
        if ($this->banner) {
            if (Storage::exists($this->banner->url)) {
                $banner = Storage::url($this->banner->url);
            } else {
                $banner = $this->banner->url;
            }
        }

        return $banner;
    }
    public function imageIconPreview() {
        $imageIcon = asset('assets/admin/media/svg/files/blank-image.svg');
        if ($this->imageIcon) {
            if (Storage::exists($this->imageIcon->url)) {
                $imageIcon = Storage::url($this->imageIcon->url);
            } else {
                $imageIcon = $this->imageIcon->url;
            }
        }

        return $imageIcon;
    }
    public function dateToString() {
        return Carbon::parse($this->created_at)->toFormattedDateString();
    }
    public function getAllChildrenIds() {
        $ids = self::getIds($this);
        $ids = array_unique($ids);

        return $ids;
    }
    public static function getCacheAllChildrenIds($productCategoryId) {
        $ids = self::getCacheIds($productCategoryId);
        $ids = array_unique($ids);

        return $ids;
    }

    // Scopes
    public function scopeAllProductsByCategory() {
        $allChildrenIds = $this->getAllChildrenIds();
        $products = Product::query()
            ->withRelations()
            ->validateProduct()
            ->whereHas('productCategories', function ($query) use ($allChildrenIds) {
                $query->whereIn('product_category_id', $allChildrenIds);
            })->get();

        return $products;
    }
    public function scopeAllChildrens($query, $productCategory) {
        $ids = self::getIds($productCategory);
        $ids = array_unique($ids);

        return $query->whereIn('id', $ids);
    }
    public function scopeValidateCategory($query) {
        return $query->where('status', true)->whereHas('products', function ($query) {
            $query->validateProduct();
        });
    }

    // Tools
    public static function getTree($category) {
        $categoryData = [
            'id' => $category->id,
            'parent_id' => $category->parent_id,
            'status' => $category->status,
            'name' => $category->getTranslations('name'),
            'slug' => $category->slug,
            'description' => $category->getTranslations('description'),
            'includeInMenu' => $category->include_in_menu,
            'order' => $category->order,
            'image' => $category->imagePreview(),
            'banner' => $category->bannerPreview(),
            'imageIcon' => $category->imageIcon ? Storage::url($category->imageIcon->url) : null,
            'productsCount' => count($category->products),
        ];
        if ($category->allChildrens->isNotEmpty()) {
            $categoryData['childrens'] = $category->allChildrens->map(function ($child) {
                return self::getTree($child);
            });
        }
        $categoryData = (object) $categoryData;
        Cache::forever('productCategory-'.$category->id, $categoryData);

        return $categoryData;
    }
    public static function getIds($category) {
        $ids = [$category->id];
        if ($category->allChildrens->isNotEmpty()) {
            $category->allChildrens->each(function ($child) use (&$ids) {
                $ids[] = $child->id;
                if ($child->allChildrens->isNotEmpty()) {
                    $ids = array_merge($ids, self::getIds($child));
                }
            });
        }

        return $ids;
    }

    // Cache
    public static function getCacheIds($productCategoryId) {
        $productCategory = self::getCache($productCategoryId);
        $ids = [$productCategoryId];
        if (isset($productCategory->childrens) && count($productCategory->childrens)) {
            $productCategory->childrens->each(function ($child) use (&$ids) {
                $ids[] = $child->id;
                if (isset($child->childrens) && count($child->childrens)) {
                    $ids = array_merge($ids, self::getCacheIds($child->id));
                }
            });
        }

        return $ids;
    }
    public static function regenerateCache($productCategoryId = null) {
        ini_set('memory_limit', '2048M');
        if ($productCategoryId) {
            $productCategory = ProductCategory::query()
                ->validateCategory()
                ->with(['products' => function ($query) {
                    $query->validateProduct();
                }])
                ->with(['allChildrens', 'image', 'banner', 'imageIcon'])
                ->find($productCategoryId);
            $productCategoryTree = self::getTree($productCategory);
            Cache::forever('productCategory-'.$productCategory->id, $productCategoryTree);
        } else {
            $productCategoriesCollect = collect();
            $productCategories = ProductCategory::query()
                ->validateCategory()
                ->with(['products' => function ($query) {
                    $query->validateProduct();
                }])
                ->with(['allChildrens', 'image', 'banner', 'imageIcon'])
                ->whereNull('parent_id')
                ->orderBy('name')
                ->get();
            foreach ($productCategories as $productCategory) {
                $productCategoryArray = self::getTree($productCategory);
                $productCategoriesCollect->push($productCategoryArray);
            }
            Cache::forever('productCategories', $productCategoriesCollect);
        }
    }
    public static function getCache($productCategoryId = null) {
        if ($productCategoryId) {
            if (! Cache::has('productCategory-'.$productCategoryId)) {
                self::regenerateCache($productCategoryId);
            }
            $productCategoryCache = Cache::get('productCategory-'.$productCategoryId);
            $productCategoryCache = self::translatableCache($productCategoryCache);

            return $productCategoryCache;
        } else {
            if (! Cache::has('productCategories')) {
                self::regenerateCache();
            }
            $productCategoriesCache = collect();
            foreach (Cache::get('productCategories') as $productCategoryCache) {
                $productCategoryCache = self::translatableCache($productCategoryCache);
                $productCategoriesCache->push($productCategoryCache);
            }

            return $productCategoriesCache;
        }
    }
    private static function translatableCache($item) {
        $language = Session::get('language');
        $languageDefault = config('translatable.fallback');
        $formattedName = isset($item->name[$language]) ? $item->name[$language] : (isset($item->name[$languageDefault]) ? $item->name[$languageDefault] : ($item->name[key($item->name)] ?? ''));
        $formattedDescription = isset($item->description[$language]) ? $item->description[$language] : (isset($item->description[$languageDefault]) ? $item->description[$languageDefault] : ($item->description[key($item->description)] ?? ''));
        $item->name = $formattedName;
        $item->description = $formattedDescription;
        if (isset($item->childrens) && count($item->childrens)) {
            foreach ($item->childrens as $key => $child) {
                $item->childrens[$key] = self::translatableCache($child);
            }
        }

        return $item;
    }
}
