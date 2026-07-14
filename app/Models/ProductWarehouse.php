<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class ProductWarehouse extends Model
{
    use HasFactory;
    use LogsActivity;

    protected $guarded = [];

    public function getActivitylogOptions(): LogOptions {
        return LogOptions::defaults()
            ->useLogName('Producto almacén')
            ->setDescriptionForEvent(fn (string $eventName) => "Un almacén de producto ha sido  {$eventName}")
            ->dontSubmitEmptyLogs()
            ->logOnlyDirty()
            ->logAll();
    }
    public function orderProductWarehouses() {
        return $this->hasMany(OrderProductWarehouse::class);
    }
    public function products() {
        return $this->belongsToMany(Product::class)
            ->withPivot('quantity')
            ->withTimestamps();
    }
    public function productVariants() {
        return $this->belongsToMany(ProductVariant::class)
            ->withPivot('quantity')
            ->withTimestamps();
    }
    public function states() {
        return $this->belongsToMany(State::class)->withPivot('priority');
    }
    public function dateToString() {
        return Carbon::parse($this->created_at)->toFormattedDateString();
    }
}
