<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Currency extends Model
{
    use HasFactory;
    use LogsActivity;

    protected $guarded = [];

    public function getActivitylogOptions(): LogOptions {
        return LogOptions::defaults()
            ->useLogName('Moneda')
            ->setDescriptionForEvent(fn (string $eventName) => "Una moneda ha sido {$eventName}")
            ->dontSubmitEmptyLogs()
            ->logOnlyDirty()
            ->logAll();
    }
    public function promotions() {
        return $this->belongsToMany(Promotion::class)->withTimestamps();
    }
    public function coupons() {
        return $this->belongsToMany(Coupon::class)->withTimestamps();
    }
    public function products() {
        return $this->morphedByMany(Product::class, 'currenciable')->withPivot(['price']);
    }
    public static function getCache() {
        if (! Cache::has('currencies')) {
            $currencies = self::validate()->orderBy('code')->get();
            Cache::put('currencies', $currencies);
        }

        return Cache::get('currencies');
    }
    public static function getDefault() {
        $currencies = self::getCache();
        $default = $currencies->where('default', true)->first();

        return $default;
    }
    public static function getCurrencyByCode($currencyCode) {
        foreach (Currency::getCache() as $currency) {
            if (strtolower($currency->code) == strtolower($currencyCode)) {
                return $currency;
            }
        }

        return null;
    }
    public function scopeValidate($query) {
        return $query->where('active', true);
    }
}
