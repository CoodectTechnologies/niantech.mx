<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderProduct extends Model
{
    use HasFactory;

    protected $table = 'order_product';
    protected $guarded = [];

    public function order() {
        return $this->belongsTo(Order::class);
    }
    public function product() {
        return $this->belongsTo(Product::class);
    }
    public function productVariant() {
        return $this->belongsTo(ProductVariant::class);
    }
    public function orderProductWarehouses() {
        return $this->hasMany(OrderProductWarehouse::class);
    }
}
