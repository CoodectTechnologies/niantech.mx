<?php

namespace App\Models;

use Carbon\Carbon;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Coupon extends Model
{
    use HasFactory;
    use LogsActivity;

    protected $guarded = [];

    public function getActivitylogOptions(): LogOptions {
        return LogOptions::defaults()
            ->useLogName('Cupón')
            ->setDescriptionForEvent(fn (string $eventName) => "Un cupón ha sido {$eventName}")
            ->dontSubmitEmptyLogs()
            ->logOnlyDirty()
            ->logAll();
    }
    public function orders() {
        return $this->hasMany(Order::class);
    }
    public function currencies() {
        return $this->belongsToMany(Currency::class)->withTimestamps();
    }
    public function products() {
        return $this->morphedByMany(Product::class, 'couponable')->withTimestamps();
    }
    public function productCategories() {
        return $this->morphedByMany(ProductCategory::class, 'couponable')->withTimestamps();
    }
    public function productBrands() {
        return $this->morphedByMany(ProductBrand::class, 'couponable')->withTimestamps();
    }
    public function dateEndToString() {
        return Carbon::parse($this->date_end)->toFormattedDateString();
    }
    public function minimumExpenseToString() {
        return '$'.number_format($this->minimum_expense, 2);
    }
    public function isTimedOut() {
        $isTimedOut = false;
        if (strtotime($this->date_end) <= strtotime(date('Y-m-d'))) {
            $isTimedOut = true;
        }

        return $isTimedOut;
    }
    public function isExceededLimitOfUse() {
        $exceededLimitOfUse = false;
        if (
            $this->limit_of_use &&
            ($this->orders()->count() >= $this->limit_of_use)
        ) {
            $exceededLimitOfUse = true;
        }

        return $exceededLimitOfUse;
    }
    public function isExcludePromotion() {
        $excludePromotion = false;
        if ($this->exclude_promotion) {
            if (Cart::instance('default')->count()) {
                foreach (Cart::instance('default')->content() as $item) {
                    if ($item->model->getPromotion() || $item->model->price_promotion) {
                        $excludePromotion = true;
                    }
                }
            }
        }

        return $excludePromotion;
    }
    public function isCurrencySessionAllowed() {
        $currencySessionAllowed = false;
        $couponCurrencies = $this->currencies->pluck('code')->toArray();
        $currencySession = currency();
        if (in_array($currencySession, $couponCurrencies)) {
            $currencySessionAllowed = true;
        }

        return $currencySessionAllowed;
    }

    // Gets
    public function isValidWithAllProductsInCart() {
        $validWithAllProductsInCart = true;
        switch ($this->type) {
            case 'Todos':
                $validWithAllProductsInCart = true;
                break;
            case 'Producto':
                $productIdsAllowed = $this->products()->pluck('couponable_id')->toArray();
                foreach (Cart::instance('default')->content() as $item) {
                    $productId = $item->id;
                    if ($this->conditional == 'Que sean') {
                        if (! in_array($productId, $productIdsAllowed)) {
                            $validWithAllProductsInCart = false;
                            break;
                        }
                    } elseif ($this->conditional == 'Que no sean') {
                        if (in_array($productId, $productIdsAllowed)) {
                            $validWithAllProductsInCart = false;
                            break;
                        }
                    }
                }
                break;
            case 'Categoría':
                $categoryIdsAllowed = $this->productCategories()->pluck('couponable_id')->toArray();
                foreach (Cart::instance('default')->content() as $item) {
                    $categoriesByProduct = $item->model->productCategories()->pluck('couponable_id')->toArray();
                    if ($this->conditional == 'Que sean') {
                        if (array_diff($categoryIdsAllowed, $categoriesByProduct)) {
                            $validWithAllProductsInCart = false;
                        }
                    } elseif ($this->conditional == 'Que no sean') {
                        if (array_intersect($categoryIdsAllowed, $categoriesByProduct)) {
                            $validWithAllProductsInCart = false;
                        }
                    }
                }
                break;
            case 'Marca':
                $brandIdsAllowed = $this->brands()->pluck('couponable_id')->toArray();
                foreach (Cart::instance('default')->content() as $item) {
                    $brandId = $item->id;
                    if ($this->conditional == 'Que sean') {
                        if (! in_array($brandId, $brandIdsAllowed)) {
                            $validWithAllProductsInCart = false;
                            break;
                        }
                    } elseif ($this->conditional == 'Que no sean') {
                        if (in_array($brandId, $brandIdsAllowed)) {
                            $validWithAllProductsInCart = false;
                            break;
                        }
                    }
                }
                break;
        }

        return $validWithAllProductsInCart;
    }
}
