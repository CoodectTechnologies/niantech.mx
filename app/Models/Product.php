<?php

namespace App\Models;

use Carbon\Carbon;
use Cviebrock\EloquentSluggable\Sluggable;
use CyrildeWit\EloquentViewable\Contracts\Viewable;
use CyrildeWit\EloquentViewable\InteractsWithViews;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Mavinoo\Batch\Traits\HasBatch;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Sitemap\Contracts\Sitemapable;
use Spatie\Sitemap\Tags\Url;
use Spatie\Translatable\HasTranslations;

class Product extends Model implements Sitemapable, Viewable
{
    use HasBatch;
    use HasFactory;
    use HasTranslations;
    use InteractsWithViews;
    use LogsActivity;
    use Sluggable;

    public const TYPE_PHYSICAL = 'Físico';

    public const TYPE_DIGITAL = 'Digital';

    public const TYPE_PHYSICAL_AND_DIGITAL = 'Físico y Digital';

    public const STATUS_PUBLISHED = 'Publicado';

    public const STATUS_DRAFT = 'Borrador';

    public const STOCK_LOW = 5;

    public const DAYS_IS_NEW = 30; // Dias a partir de la creación

    public const COOKIE_PRODUCT_VIEW_RECENTS = 'product-view-recents';

    protected $guarded = [];
    protected $removeViewsOnDelete = true;
    public $translatable = ['name', 'name_commercial', 'detail', 'description', 'search_advanced', 'meta_title', 'meta_description', 'meta_keywords'];

    public function getActivitylogOptions(): LogOptions {
        return LogOptions::defaults()
            ->useLogName('Producto')
            ->setDescriptionForEvent(fn (string $eventName) => "Un producto ha sido  {$eventName}")
            ->dontSubmitEmptyLogs()
            ->logOnlyDirty()
            ->logAll();
    }
    public function toSitemapTag(): Url|string|array {
        return route('ecommerce.product.show', $this);
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
    public function user() {
        return $this->belongsTo(User::class);
    }
    public function image() {
        return $this->morphOne(Image::class, 'imageable')->where('main', true);
    }
    public function images() {
        return $this->morphMany(Image::class, 'imageable')->whereNull('main');
    }
    public function imagesVadetoBrands() {
        return $this->morphMany(Image::class, 'imageable')->whereNull('main')->where('name', 'imagesVadetoBrands');
    }
    public function currency() {
        return $this->belongsTo(Currency::class);
    }
    public function comments() {
        return $this->morphMany(Comment::class, 'commentable');
    }
    public function promotions() {
        return $this->morphToMany(Promotion::class, 'promotionable')->withTimestamps();
    }
    public function coupons() {
        return $this->morphToMany(Coupon::class, 'couponable')->withTimestamps();
    }
    public function wholesales() {
        return $this->belongsToMany(Wholesale::class);
    }
    public function orders() {
        return $this->belongsToMany(Order::class)->withTimestamps()->withPivot(['type', 'quantity', 'price', 'subtotal', 'created_at']);
    }
    public function productWarehouses() {
        return $this->belongsToMany(ProductWarehouse::class)->withTimestamps()->withPivot(['quantity', 'created_at', 'updated_at']);
    }
    public function productGenders() {
        return $this->belongsToMany(ProductGender::class);
    }
    public function productSimilars() {
        return $this->hasMany(ProductSimilar::class)->with('product');
    }
    public function productCategories() {
        return $this->belongsToMany(ProductCategory::class);
    }
    public function productBrand() {
        return $this->belongsTo(ProductBrand::class);
    }
    public function shippingClass() {
        return $this->belongsTo(ShippingClass::class);
    }
    public function configuratorStages() {
        return $this->belongsToMany(ConfiguratorStage::class);
    }
    public function configuratorCompatibilities() {
        return $this->belongsToMany(ConfiguratorCompatibility::class);
    }
    public function unitType() {
        return $this->belongsTo(UnitType::class);
    }
    public function productAttributes() {
        return $this->hasMany(ProductAttribute::class);
    }
    public function productCharacteristics() {
        return $this->hasMany(ProductCharacteristic::class);
    }
    public function productVariants() {
        return $this->hasMany(ProductVariant::class)->orderBy('position');
    }

    // Gets
    public function viewUniques() {
        return views($this)->unique()->count();
    }
    public function getPromotion() {
        // Si ya se calculó previamente, retornar los valores cacheados
        if (isset($this->promotion_percentage)) {
            return $this->promotion_object ?? null;
        }
        $promotion = null;
        // Si el producto tiene variantes, solo considerar promociones masivas
        $hasVariants = $this->productVariants->count();
        // Prioridad 1: Promoción directa del producto (solo si NO tiene variantes)
        if (! $hasVariants && $this->price_promotion && $this->price_promotion > 0 && $this->price_promotion < $this->price) {
            $this->promotion_price = $this->price_promotion;
            $this->promotion_percentage = round((($this->price - $this->price_promotion) / $this->price) * 100, 2);
            $this->promotion_object = null; // No es una promoción masiva
        } else {
            // Prioridad 2: Promoción masiva
            $promotion = Promotion::getPromotionProduct($this);
            if ($promotion) {
                $this->promotion_price = $this->price - ((round($promotion->percentage, 2) / 100) * $this->price);
                $this->promotion_percentage = round($promotion->percentage, 2);
                $this->promotion_object = $promotion;
            } else {
                // Sin promociones
                $this->promotion_price = 0;
                $this->promotion_percentage = 0;
                $this->promotion_object = null;
            }
        }

        return $promotion;
    }
    public function getPromotionPercentageMax() {
        /**
         * Obtener el porcentaje de promoción más alto entre el producto y sus variantes
         * Útil para mostrar badges de descuento
         */
        $maxPercentage = 0;
        // Verificar promoción del producto base
        if (! isset($this->promotion_percentage)) {
            $this->getPromotion();
        }
        $maxPercentage = $this->promotion_percentage ?? 0;
        // Verificar promociones de variantes
        foreach ($this->productVariants as $variant) {
            if ($variant->price_promotion && $variant->price_promotion > 0 && $variant->price_promotion < $variant->price) {
                $variantPercentage = round((($variant->price - $variant->price_promotion) / $variant->price) * 100, 2);
                if ($variantPercentage > $maxPercentage) {
                    $maxPercentage = $variantPercentage;
                }
            }
        }

        return $maxPercentage;
    }
    public function hasActivePromotion() {
        // Verificar promoción del producto base
        if (! isset($this->promotion_percentage)) {
            $this->getPromotion();
        }
        if ($this->promotion_percentage > 0) {
            return true;
        }
        // Verificar promociones en variantes
        if ($this->productVariants && $this->productVariants->count() > 0) {
            foreach ($this->productVariants as $variant) {
                if ($variant->price_promotion && $variant->price_promotion > 0 && $variant->price_promotion < $variant->price) {
                    return true;
                }
            }
        }

        return false;
    }
    public function getWholesale() {
        return Wholesale::getWholesale($this);
    }
    public function getPriceToString() {
        $sessionCurrency = Session::get('currency');

        if ($this->productVariants->count()) {
            $priceVariantMin = null;
            $priceVariantMax = null;
            $pricePromotionMin = null;
            $pricePromotionMax = null;
            $hasAnyPromotion = false;

            foreach ($this->productVariants as $variant) {
                $priceVariant = $variant->getPrice();
                $pricePromotion = $variant->getPricePromotion();
                $priceFinal = $variant->getPriceFinal();
                // Rastrear si alguna variante tiene promoción
                if ($pricePromotion > 0) {
                    $hasAnyPromotion = true;
                }
                // Calcular mínimos para precio regular
                if (is_null($priceVariantMin) || $priceVariant < $priceVariantMin) {
                    $priceVariantMin = $priceVariant;
                }
                if (is_null($priceVariantMax) || $priceVariant > $priceVariantMax) {
                    $priceVariantMax = $priceVariant;
                }
                // Calcular mínimos para precio final (con o sin promoción)
                if (is_null($pricePromotionMin) || $priceFinal < $pricePromotionMin) {
                    $pricePromotionMin = $priceFinal;
                }
                if (is_null($pricePromotionMax) || $priceFinal > $pricePromotionMax) {
                    $pricePromotionMax = $priceFinal;
                }
            }
            // Si hay promociones en alguna variante
            if ($hasAnyPromotion) {
                if ($pricePromotionMin == $pricePromotionMax) {
                    // Todas las variantes tienen el mismo precio final
                    $priceToString = '<del class="old-price">'.currencySymbol().number_format($priceVariantMin, config('cart.format.decimals')).'<span class="price-currency">'.$sessionCurrency.'</span></del>';
                    $priceToString .= '<ins class="new-price-promo">'.currencySymbol().number_format($pricePromotionMin, config('cart.format.decimals')).'<span class="price-currency">'.$sessionCurrency.'</span></ins>';
                } else {
                    // Rango de precios con promoción
                    $priceToString = '<del class="old-price">'.currencySymbol().number_format($priceVariantMin, config('cart.format.decimals')).' - '.currencySymbol().number_format($priceVariantMax, config('cart.format.decimals')).' <span class="price-currency">'.$sessionCurrency.'</span></del>';
                    $priceToString .= '<ins class="new-price-promo">'.currencySymbol().number_format($pricePromotionMin, config('cart.format.decimals')).' - '.currencySymbol().number_format($pricePromotionMax, config('cart.format.decimals')).' <span class="price-currency">'.$sessionCurrency.'</span></ins>';
                }
            } else {
                // Sin promociones
                if ($priceVariantMin == $priceVariantMax) {
                    return '<ins class="new-price">'.currencySymbol().number_format($priceVariantMin, config('cart.format.decimals')).'<span class="price-currency">'.$sessionCurrency.'</span></ins>';
                } else {
                    return '<ins class="new-price">'.currencySymbol().number_format($priceVariantMin, config('cart.format.decimals')).' - '.currencySymbol().number_format($priceVariantMax, config('cart.format.decimals')).' '.$sessionCurrency.'</ins>';
                }
            }

            return $priceToString;
        } else {
            // Producto sin variantes (código original)
            $priceToString = '<ins class="new-price">'.currencySymbol().number_format($this->getPrice(), config('cart.format.decimals')).'<span class="price-currency"> '.$sessionCurrency.'</span</ins>';
            if ($pricePromotion = $this->getPricePromotion()) {
                $priceToString = '<del class="old-price">'.$priceToString.'</del> '.$pricePromotion.' '.$sessionCurrency;
                $pricePromotion = '<ins class="new-price-promo">'.currencySymbol().number_format($pricePromotion, config('cart.format.decimals')).'<span class="price-currency"> '.$sessionCurrency.'</span</ins>';
            }
        }

        return $priceToString;
    }
    public function getPrice() {
        $currencyProduct = $this->currency; // Objeto del modelo de la moneda relacionada al producto
        if (! $currencyProduct) {
            $currencyProduct = Currency::getDefault();
        }
        $price = $this->price;
        if (config('cart.tax') && ! config('cart.products_already_include_tax')) {
            $price = $price + ($price * (config('cart.tax') / 100));
            $price = round($price, 0);
        }
        $price = convertCurrencyBySession($price, $currencyProduct->code, $currencyProduct->value, $currencyProduct->default);

        return $price;
    }
    public function getPricePromotion() {
        $pricePromotion = 0;
        if (! isset($this->promotion_percentage)) {
            $this->getPromotion();
        }
        if ($this->promotion_percentage > 0) {
            $currencyProduct = $this->currency; // Objeto del modelo de la moneda relacionada al producto
            if (! $currencyProduct) {
                $currencyProduct = Currency::getDefault();
            }
            $pricePromotion = convertCurrencyBySession($this->promotion_price, $currencyProduct->code, $currencyProduct->value, $currencyProduct->default);
        }

        return $pricePromotion;
    }
    public function getPriceFinal() {
        $priceFinal = 0;
        if ($pricePromotion = $this->getPricePromotion()) {
            $priceFinal = $pricePromotion;
        } else {
            $priceFinal = $this->getPrice();
        }

        return $priceFinal;
    }
    public function getQuantityTotal() {
        $quantity = 0;
        if (count($this->productVariants)) {
            foreach ($this->productVariants as $variant) {
                $quantity += $variant->getQuantityTotal();
            }
        } else {
            $quantity = $this->productWarehouses->sum('pivot.quantity');
        }

        return $quantity;
    }
    public function getStatusToString() {
        if ($this->status == self::STATUS_DRAFT) {
            return '<div class="badge badge-light-warning">'.$this->status.'</div>';
        } elseif ($this->status == self::STATUS_PUBLISHED) {
            return '<div class="badge badge-light-success">'.$this->status.'</div>';
        } else {
            return '<div class="badge badge-light-danger">Desconocido</div>';
        }
    }
    public function getIsNew() {
        $isNew = false;
        $daysExpiredNew = self::DAYS_IS_NEW; // Si un producto llega a los 7 días de ser creado, ya no será considerado nuevo
        $diffTime = Carbon::parse($this->created_at)->diffInDays(date('Y-m-d'));
        if ($diffTime <= $daysExpiredNew) {
            $isNew = true;
        }

        return $isNew;
    }
    public function getIsInStock() {
        return $this->getQuantityTotal() ? true : false;
    }
    public function dateToString() {
        return Carbon::parse($this->created_at)->toFormattedDateString();
    }
    public function getName() {
        $name = $this->name;
        if ($this->name_commercial) {
            $name = $this->name_commercial;
        }

        return $name;
    }
    public function imagePreview() {
        $image = asset('assets/admin/media/product/default.png');
        if ($this->image) {
            if (Storage::exists($this->image->url)) {
                $image = Storage::url($this->image->url);
            } else {
                if ($this->image->url) {
                    $image = $this->image->url;
                }
            }
        }

        return $image;
    }
    public function imagesPreview() {
        $images = collect();
        if (! $images->count()) {
            if (count($this->images)) {
                foreach ($this->images as $image) {
                    if (Storage::exists($image->url)) {
                        $images->push(Storage::url($image->url));
                    } else {
                        if ($image->url) {
                            $images->push($image->url);
                        }
                    }
                }
            }
        }

        return $images;
    }
    public function getStarsAVG() {
        $starsAVG = 5;
        $commentStars = $this->comments->sum('stars');
        $commentCounts = $this->comments->count();
        if ($commentStars && $commentCounts) {
            $starsAVG = number_format(($commentStars / $commentCounts), 1);
        }

        return $starsAVG;
    }
    public function getStarsPercentageAVG() {
        $getStarsAVG = $this->getStarsAVG();

        return ($getStarsAVG * 100) / 5;
    }
    public function getStarsPercentage($qty) {
        $starsPercentage = 0;
        $commentsTotal = $this->comments->count();
        $commentCounts = $this->comments->where('stars', $qty)->count();
        if ($commentCounts) {
            $starsPercentage = ($commentCounts * 100) / $commentsTotal;
        }

        return floor($starsPercentage);
    }
    public function getIsPhysical() {
        return ($this->type == self::TYPE_PHYSICAL || $this->type == self::TYPE_PHYSICAL_AND_DIGITAL || ! $this->type) ? true : false;
    }
    public function getIsDigital() {
        return ($this->type == self::TYPE_DIGITAL || $this->type == self::TYPE_PHYSICAL_AND_DIGITAL) ? true : false;
    }
    public function getType() {
        return $this->type ?? Product::TYPE_PHYSICAL;
    }
    public function getIsDownloadable() {
        return ($this->getIsDigital() && $this->downloadable) ? true : false;
    }
    public function getFileDigital() {
        $fileDigital = '';
        if ($this->file_digital) {
            if (Storage::exists($this->file_digital)) {
                $fileDigital = Storage::url($this->file_digital);
            } else {
                $fileDigital = $this->file_digital;
            }
        }

        return $fileDigital;
    }
    public static function getViewRecents() {
        $products = [];
        if (Cookie::has(self::COOKIE_PRODUCT_VIEW_RECENTS)) {
            $productIds = json_decode(Cookie::get(self::COOKIE_PRODUCT_VIEW_RECENTS), true);
            if (! empty($productIds)) {
                $products = self::query()->withRelations()->whereIn('id', $productIds)->get();
            }
        }

        return $products;
    }
    public static function getTypes() {
        return [self::TYPE_PHYSICAL, self::TYPE_DIGITAL, self::TYPE_PHYSICAL_AND_DIGITAL];
    }
    protected function getCurrencySessionValue() {
        $currencySession = Currency::getCurrencyByCode(session()->get('currency'));

        return $currencySession ? $currencySession->value : (Currency::getDefault()?->value ?? 1);
    }

    // Scopes
    public function scopeValidateProduct($query) {
        return $query->where('status', self::STATUS_PUBLISHED)
            ->where('price', '>', 0);
    }
    public function scopeMostSelled($query) {
        $productsIds = Product::has('orders')
            ->withSum('orders as total_quantity', 'order_product.quantity')
            ->orderByDesc('total_quantity')
            ->limit(10)
            ->pluck('id');

        return $query->whereIn('id', $productsIds);
    }
    public function scopeWithRelations($query) {
        return $query->with([
            'image',
            'images',
            'productCategories',
            'currency',
            'productWarehouses',
            'productSimilars',
            'productAttributes',
            'productCharacteristics',
            'productVariants.productOptionValues.productOption',
            'productVariants.productWarehouses',
        ])->with(['comments' => function ($query) {
            $query->validate();
        }])->with(['productVariants' => function ($query) {
            $query->where('is_active', 1);
        }]);
    }
    public function scopeWithConvertedPrice($query) {
        $currencyValue = $this->getCurrencySessionValue();

        return $query->leftJoin('currencies as product_currencies', 'products.currency_id', '=', 'product_currencies.id')
            ->select('products.*')
            ->selectRaw($this->_convertedPriceSqlRaw().' as price_converted', [$currencyValue]);
    }
    public function scopeOrderByConvertedPrice($query, $direction = 'asc') {
        $currencyValue = $this->getCurrencySessionValue();
        $direction = strtolower($direction) === 'desc' ? 'desc' : 'asc';

        return $query->orderByRaw($this->_convertedPriceSqlRaw()." {$direction}", [$currencyValue]);
    }
    public function scopeFilterByConvertedPrice($query, $minPrice = null, $maxPrice = null) {
        $currencyValue = $this->getCurrencySessionValue();
        if ($minPrice !== null) {
            $query->whereRaw($this->_convertedPriceSqlRaw().' >= ?', [$currencyValue, $minPrice]);
        }
        if ($maxPrice !== null) {
            $query->whereRaw($this->_convertedPriceSqlRaw().' <= ?', [$currencyValue, $maxPrice]);
        }

        return $query;
    }
    public function scopeHasPromotions($query) {
        $productInIds = [];
        $productNotInIds = [];
        $categoryInIds = [];
        $categoryNotInIds = [];
        $brandInIds = [];
        $brandNotInIds = [];
        $promotions = Promotion::query()
            ->with(['productCategories', 'productBrands', 'products'])
            ->where('active', true)
            ->whereHas('currencies', function ($query) {
                $query->where('code', Session::get('currency'));
            })
            ->whereDate('date_start', '<=', date('Y-m-d'))
            ->whereDate('date_end', '>', date('Y-m-d'))
            ->orderByDesc('id')
            ->cursor();
        if (count($promotions)) {
            foreach ($promotions as $promotion) {
                // BY TODO
                if ($promotion->type == 'Todos') {
                    return $query;
                }
                // BY PRODUCT
                if (
                    $promotion->type == 'Producto' &&
                    count($promotion->products)
                ) {
                    foreach ($promotion->products as $product) {
                        if ($promotion->conditional == 'Que sean') {
                            $productInIds[$product->id] = $product->id;
                        } else {
                            $productNotInIds[$product->id] = $product->id;
                        }
                    }
                }
                // BY CATEGORIES
                if (
                    $promotion->type == 'Categoría' &&
                    count($promotion->productCategories)
                ) {
                    foreach ($promotion->productCategories as $productCategory) {
                        if ($promotion->conditional == 'Que sean') {
                            $categoryInIds[$productCategory->id] = $productCategory->id;
                        } else {
                            $categoryNotInIds[$productCategory->id] = $productCategory->id;
                        }
                    }
                }
                // BY BRAND
                if (
                    $promotion->type == 'Marca' &&
                    count($promotion->productBrands)
                ) {
                    foreach ($promotion->productBrands as $productBrand) {
                        if ($promotion->conditional == 'Que sean') {
                            $categoryInIds[$productBrand->id] = $productBrand->id;
                        } else {
                            $categoryNotInIds[$productBrand->id] = $productBrand->id;
                        }
                    }
                }
            }
            // BY PRODUCT
            if (count($productInIds)) {
                $query->whereIn('id', array_keys($productInIds));
            }
            if (count($productNotInIds)) {
                $query->whereNotIn('id', array_keys($productNotInIds));
            }
            // BY CATEGORIES
            if (count($categoryInIds)) {
                if (count($productInIds) || count($productNotInIds)) {
                    $query->orWhere(function ($query) use ($categoryInIds) {
                        $this->_scopePromotionByCategories($query, 'in', $categoryInIds);
                    });
                } else {
                    $query->where(function ($query) use ($categoryInIds) {
                        $this->_scopePromotionByCategories($query, 'in', $categoryInIds);
                    });
                }
            }
            if (count($categoryNotInIds)) {
                if (count($productInIds) || count($productNotInIds)) {
                    $query->orWhere(function ($query) use ($categoryNotInIds) {
                        $this->_scopePromotionByCategories($query, 'not_in', $categoryNotInIds);
                    });
                } else {
                    $query->where(function ($query) use ($categoryNotInIds) {
                        $this->_scopePromotionByCategories($query, 'not_in', $categoryNotInIds);
                    });
                }
            }
            // BY BRAND
            if (count($brandInIds)) {
                if (count($productInIds) || count($productNotInIds)) {
                    $query->orWhere(function ($query) use ($brandInIds) {
                        $this->_scopePromotionByBrands($query, 'in', $brandInIds);
                    });
                } else {
                    $query->where(function ($query) use ($brandInIds) {
                        $this->_scopePromotionByBrands($query, 'in', $brandInIds);
                    });
                }
            }
            if (count($brandNotInIds)) {
                if (count($productInIds) || count($productNotInIds)) {
                    $query->orWhere(function ($query) use ($brandNotInIds) {
                        $this->_scopePromotionByBrands($query, 'not_in', $brandNotInIds);
                    });
                } else {
                    $query->where(function ($query) use ($brandNotInIds) {
                        $this->_scopePromotionByBrands($query, 'not_in', $brandNotInIds);
                    });
                }
            }
        } else {
            $query->has('promotions')->whereHas('promotions', function ($query) {
                $query->where('active', true)
                    ->whereHas('currencies', function ($query) {
                        $query->where('code', Session::get('currency'));
                    })
                    ->whereDate('date_start', '<=', date('Y-m-d'))
                    ->whereDate('date_end', '>', date('Y-m-d'));
            });
        }
        $query->orWhere('price_promotion', '>', 0);

        return $query;
    }

    // Tools partial scopes
    private function _scopePromotionByCategories($query, $type, $categoryIds) {
        $query->whereHas('productCategories', function ($query) use ($type, $categoryIds) {
            if ($type == 'in') {
                $query->whereIn('product_category_id', array_keys($categoryIds));
            } else {
                $query->whereNotIn('product_category_id', array_keys($categoryIds));
            }
        });

        return $query;
    }
    private function _scopePromotionByBrands($query, $type, $brandInIds) {
        if ($type == 'in') {
            $query->whereIn('product_brand_id', array_keys($brandInIds));
        } else {
            $query->whereNotIn('product_brand_id', array_keys($brandInIds));
        }

        return $query;
    }
    private function _convertedPriceSqlRaw(): string {
        return '(products.price * COALESCE(product_currencies.value, 1)) / ?';
    }
}
