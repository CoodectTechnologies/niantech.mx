<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderProvider extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function order() {
        return $this->belongsTo(Order::class);
    }
    public function productWarehouse() {
        return $this->belongsTo(ProductWarehouse::class);
    }
}
