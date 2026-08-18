<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;

class ProductOption extends Model
{
    use Sluggable;

    public const TYPE_SELECT = 'select';
    public const TYPE_BUTTON = 'button';
    public const TYPE_COLOR = 'color';
    public const TYPE_IMAGE = 'image';
    
    protected $guarded = [];

    public function sluggable(): array {
        return [
            'slug' => [
                'source' => 'name',
            ],
        ];
    }
    public function productOptionValues() {
        return $this->hasMany(ProductOptionValue::class);
    }
}
