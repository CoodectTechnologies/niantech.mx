<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductOptionValue extends Model
{
    protected $guarded = [];

    // Relación con la opción del catálogo global
    public function productOption() {
        return $this->belongsTo(ProductOption::class);
    }

    // Variantes que usan este valor
    public function productVariants() {
        return $this->belongsToMany(
            ProductVariant::class,
            'product_variant_options',
            'product_option_value_id',
            'product_variant_id'
        );
    }
}
