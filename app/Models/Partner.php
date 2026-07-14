<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Translatable\HasTranslations;

class Partner extends Model
{
    use HasFactory;
    use HasTranslations;
    use LogsActivity;

    const CACHE_KEY = 'partners';

    protected $guarded = [];
    public $translatable = ['name'];

    public function getActivitylogOptions(): LogOptions {
        return LogOptions::defaults()
            ->useLogName('Socio')
            ->setDescriptionForEvent(fn (string $eventName) => "Un socio ha sido  {$eventName}")
            ->dontSubmitEmptyLogs()
            ->logOnlyDirty()
            ->logAll();
    }
    public function image() {
        return $this->morphOne(Image::class, 'imageable');
    }
    public function imagePreview() {
        $image = asset('assets/admin/media/svg/files/blank-image.svg');
        if ($this->image) {
            if (Storage::exists($this->image->url)) {
                $image = Storage::url($this->image->url);
            } else {
                $image = $this->image->url;
            }
        }

        return $image;
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
        $partners = Partner::with(['image'])->orderBy('created_at', 'desc')->get();
        Cache::put(self::CACHE_KEY, $partners);
    }
}
