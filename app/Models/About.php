<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Translatable\HasTranslations;

class About extends Model
{
    use HasFactory;
    use HasTranslations;
    use LogsActivity;

    const CACHE_KEY = 'about';

    protected $guarded = [];
    public $translatable = ['title', 'information', 'mission', 'vision', 'values'];

    public function getActivitylogOptions(): LogOptions {
        return LogOptions::defaults()
            ->useLogName('Nosotros')
            ->setDescriptionForEvent(fn (string $eventName) => "Información de nosotros ha sido {$eventName}")
            ->dontSubmitEmptyLogs()
            ->logOnlyDirty()
            ->logAll();
    }
    public function image() {
        return $this->morphOne(Image::class, 'imageable')->whereNull('name')->where('main', true);
    }
    public function image2() {
        return $this->morphOne(Image::class, 'imageable')->where('name', 'image2')->where('main', true);
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
    public function image2Preview() {
        $image2 = asset('assets/admin/media/svg/files/blank-image.svg');
        if ($this->image2) {
            if (Storage::exists($this->image2->url)) {
                $image2 = Storage::url($this->image2->url);
            } else {
                $image2 = $this->image2->url;
            }
        }

        return $image2;
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
        $about = About::with(['image', 'image2'])->first() ?? new About;
        Cache::put(self::CACHE_KEY, $about);
    }
}
