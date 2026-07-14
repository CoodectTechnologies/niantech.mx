<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class UnitType extends Model
{
    use HasFactory;
    use LogsActivity;

    public const KEY_CACHE = 'unitTypes';

    protected $guarded = [];
    public $translatable = ['name', 'position', 'body'];

    public function getActivitylogOptions(): LogOptions {
        return LogOptions::defaults()
            ->useLogName('Tipo de unidad')
            ->setDescriptionForEvent(fn (string $eventName) => "Un tipo de unidad ha sido {$eventName}")
            ->dontSubmitEmptyLogs()
            ->logOnlyDirty()
            ->logAll();
    }
    public function product() {
        return $this->hasOne(Product::class);
    }
    public static function getCache() {
        if (! Cache::has(self::KEY_CACHE)) {
            self::regenerateCache();
        }

        return Cache::get(self::KEY_CACHE);
    }
    public static function getUnitTypeIdByCode($unitTypeCode) {
        foreach (self::getCache() as $unitType) {
            if ($unitType->code == $unitTypeCode) {
                return $unitType->id;
            }
        }

        return null;
    }
    public static function regenerateCache() {
        $unitTypes = self::orderBy('code')->get();
        Cache::put(self::KEY_CACHE, $unitTypes);
    }
}
