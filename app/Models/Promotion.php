<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Promotion extends Model
{
    use HasFactory;
    use LogsActivity;

    public const KEY_CACHE = 'promotions';

    protected $guarded = [];

    public function getActivitylogOptions(): LogOptions {
        return LogOptions::defaults()
            ->useLogName('Promoción')
            ->setDescriptionForEvent(fn (string $eventName) => "Una promoción ha sido {$eventName}")
            ->dontSubmitEmptyLogs()
            ->logOnlyDirty()
            ->logAll();
    }
    public function currencies() {
        return $this->belongsToMany(Currency::class)->withTimestamps();
    }
    public function products() {
        return $this->morphedByMany(Product::class, 'promotionable')->withTimestamps();
    }
    public function productCategories() {
        return $this->morphedByMany(ProductCategory::class, 'promotionable')->withTimestamps();
    }
    public function productBrands() {
        return $this->morphedByMany(ProductBrand::class, 'promotionable')->withTimestamps();
    }

    // TOOLS
    public function dateStartToString() {
        return Carbon::parse($this->date_start)->toFormattedDateString();
    }
    public function dateEndToString() {
        return Carbon::parse($this->date_end)->toFormattedDateString();
    }

    // Gets
    public static function getPromotionProduct(Product $product) {
        $oPromotion = null;
        $promotions = Promotion::getCache();
        if (count($promotions)) {
            $promotions = $promotions->filter(function ($promotion) {
                return $promotion->date_start <= date('Y-m-d') &&
                       $promotion->date_end > date('Y-m-d') &&
                       $promotion->currencies->contains('code', Session::get('currency'));
            });
            foreach ($promotions as $promotion) {
                switch ($promotion->type) {
                    case 'Todos':
                        $oPromotion = $promotion;
                        break;
                    case 'Producto':
                        $productId = $product->id;
                        $products = $promotion->products();
                        $applyPromotion = false;
                        if ($promotion->conditional == 'Que sean') {
                            $applyPromotion = $products->where('promotionable_id', $productId)->exists();
                        } elseif ($promotion->conditional == 'Que no sean') {
                            $applyPromotion = ! $products->where('promotionable_id', $productId)->exists();
                        }
                        if ($applyPromotion) {
                            $oPromotion = $promotion;
                        }
                        break;
                    case 'Categoría':
                        $categoryIds = $product->productCategories()->pluck('product_category_id')->toArray();
                        $productCategories = $promotion->productCategories();
                        $applyPromotion = false;
                        if ($promotion->conditional == 'Que sean') {
                            $applyPromotion = ! empty($categoryIds) && $productCategories->whereIn('promotionable_id', $categoryIds)->exists();
                        } elseif ($promotion->conditional == 'Que no sean') {
                            $applyPromotion = empty($categoryIds) || ! $productCategories->whereIn('promotionable_id', $categoryIds)->exists();
                        }
                        if ($applyPromotion) {
                            $oPromotion = $promotion;
                        }
                        break;
                    case 'Marca':
                        $brandId = $product->product_brand_id;
                        $productBrands = $promotion->productBrands();
                        $applyPromotion = false;
                        if ($promotion->conditional == 'Que sean') {
                            $applyPromotion = ! is_null($brandId) && $productBrands->where('promotionable_id', $brandId)->exists();
                        } elseif ($promotion->conditional == 'Que no sean') {
                            $applyPromotion = is_null($brandId) || ! $productBrands->where('promotionable_id', $brandId)->exists();
                        }
                        if ($applyPromotion) {
                            $oPromotion = $promotion;
                        }
                        break;
                }
            }
        }

        return $oPromotion;
    }

    // CACHE
    public static function getCache() {
        if (! Cache::has(self::KEY_CACHE)) {
            self::regenerateCache();
        }

        return Cache::get(self::KEY_CACHE);
    }
    public static function regenerateCache() {
        $promotions = self::with(['currencies'])->where('active', true)->orderByDesc('id')->get();
        Cache::put(self::KEY_CACHE, $promotions);
    }
}
