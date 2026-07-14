<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Gallery extends Model
{
    use HasFactory;
    use LogsActivity;

    const CACHE_KEY = 'gallery';

    protected $guarded = [];

    public function getActivitylogOptions(): LogOptions {
        return LogOptions::defaults()
            ->useLogName('Galería')
            ->setDescriptionForEvent(fn (string $eventName) => "La galería ha sido {$eventName}")
            ->dontSubmitEmptyLogs()
            ->logOnlyDirty()
            ->logAll();
    }
    public function images() {
        return $this->morphMany(Image::class, 'imageable');
    }
    public function moduleWeb() {
        return $this->belongsTo(ModuleWeb::class);
    }
    public function dateToString() {
        return Carbon::parse($this->created_at)->toFormattedDateString();
    }
    public static function getCache() {
        if (! Cache::has(self::CACHE_KEY)) {
            self::regenerateCache();
        }

        return Cache::get(self::CACHE_KEY);
    }
    public static function clearCache() {
        Cache::forget(self::CACHE_KEY);
    }
    public static function regenerateCache() {
        $gallery = Gallery::with(['images', 'moduleWeb'])->orderBy('created_at', 'desc')->get();
        Cache::put(self::CACHE_KEY, $gallery);
    }
}
