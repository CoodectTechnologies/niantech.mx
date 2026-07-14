<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderProductWarehouse extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function orderProduct() {
        return $this->belongsTo(OrderProduct::class);
    }
    public function productWarehouse() {
        return $this->belongsTo(ProductWarehouse::class);
    }
}
