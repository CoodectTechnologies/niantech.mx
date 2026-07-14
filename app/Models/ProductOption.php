<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ProductOption extends Model
{
    protected $guarded = [];

    protected static function boot() {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->slug)) {
                $model->slug = Str::slug($model->name);
            }
        });
    }

    // Valores de esta opción
    public function productOptionValues() {
        return $this->hasMany(ProductOptionValue::class);
    }
}
