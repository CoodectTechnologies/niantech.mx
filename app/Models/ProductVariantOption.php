<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductVariantOption extends Model
{
    protected $guarded = [];

    public function productVariant() {
        return $this->belongsTo(ProductVariant::class);
    }
    public function productOptionValue() {
        return $this->belongsTo(ProductOptionValue::class);
    }
}
