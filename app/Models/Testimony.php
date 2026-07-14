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

class Testimony extends Model
{
    use HasFactory;
    use HasTranslations;
    use LogsActivity;

    const CACHE_KEY = 'testimonies';

    protected $guarded = [];
    public $translatable = ['name', 'position', 'body'];

    public function getActivitylogOptions(): LogOptions {
        return LogOptions::defaults()
            ->useLogName('Testimonio')
            ->setDescriptionForEvent(fn (string $eventName) => "Un testimonio ha sido {$eventName}")
            ->dontSubmitEmptyLogs()
            ->logOnlyDirty()
            ->logAll();
    }
    public function image() {
        return $this->morphOne(Image::class, 'imageable');
    }
    public function imagePreview() {
        $image = asset('assets/admin/media/avatars/blank.png');
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
    public function abreviature() {
        $name = $this->name;
        $ab1 = isset($name[0]) ? $name[0] : '';
        $ab2 = isset($name[1]) ? $name[1] : '';

        return strtoupper($ab1).strtoupper($ab2);
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
        $testimonies = Testimony::with(['image'])->get();
        Cache::put(self::CACHE_KEY, $testimonies);
    }
}
