<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

class ProductVariant extends Model
{
    protected $guarded = [];
    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function product() {
        return $this->belongsTo(Product::class);
    }
    public function productVariantOptions() {
        return $this->hasMany(ProductVariantOption::class);
    }
    public function productOptionValues() {
        return $this->belongsToMany(
            ProductOptionValue::class,
            'product_variant_options',
            'product_variant_id',
            'product_option_value_id'
        );
    }
    public function productWarehouses() {
        return $this->belongsToMany(ProductWarehouse::class)
            ->withPivot('quantity')
            ->withTimestamps();
    }
    public function images() {
        return $this->morphMany(Image::class, 'imageable')->whereNull('main');
    }

    // Helpers images
    public function imagePreview() {
        foreach ($this->images as $image) {
            return Storage::url($image->url);
        }

        return $this->product->imagePreview();
    }

    // Helpers warehouse
    public function getQuantityTotal() {
        return $this->productWarehouses->sum('pivot.quantity');
    }
    public function getIsInStock() {
        return $this->getQuantityTotal() > 0;
    }

    // Helpers variations
    public function getOptionValuesLabel() {
        return $this->productOptionValues
            ->pluck('value')
            ->implode(' / ');
    }

    // Helpers price
    public function getPrice() {
        $currencyProduct = $this->product->currency; // Objeto del modelo de la moneda relacionada al producto
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
        if ($this->price_promotion) {
            $currencyProduct = $this->product->currency; // Objeto del modelo de la moneda relacionada al producto
            if (! $currencyProduct) {
                $currencyProduct = Currency::getDefault();
            }
            $pricePromotion = convertCurrencyBySession($this->price_promotion, $currencyProduct->code, $currencyProduct->value, $currencyProduct->default);
        } else {
            if ($promotion = Promotion::getPromotionProduct($this->product)) {
                if ($promotion->include_to_variant) {
                    $price = $this->getPrice();
                    $pricePromotion = ($price - ((($promotion->percentage / 100)) * $price));
                }
            }
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
    public function getPriceToString() {
        $sessionCurrency = Session::get('currency');
        if ($pricePromotion = $this->getPricePromotion()) {
            $priceToString = '
                <del class="old-price">'.number_format($this->getPrice(), config('cart.format.decimals')).'<span class="price-currency">'.$sessionCurrency.'</span></del>
                <ins class="new-price">'.currencySymbol().number_format($pricePromotion, config('cart.format.decimals')).'<span class="price-currency">'.$sessionCurrency.'</span></ins>
            ';
        } else {
            $priceToString = '<ins class="new-price">'.$this->getPrice().' '.$sessionCurrency.'</ins>';
        }

        return $priceToString;
    }

    // Scopes
    public function scopeValidateVariant($query) {
        return $query->where('is_active', true)->where('price', '>', 0);
    }
}
