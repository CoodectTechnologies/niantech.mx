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

class Banner extends Model
{
    use HasFactory;
    use HasTranslations;
    use LogsActivity;

    const CACHE_KEY = 'banners';

    protected $guarded = [];
    public $translatable = ['title', 'subtitle', 'description', 'btn_text'];
    public $casts = [
        'overlay' => 'boolean',
    ];

    public function getActivitylogOptions(): LogOptions {
        return LogOptions::defaults()
            ->useLogName('Banner')
            ->setDescriptionForEvent(fn (string $eventName) => "Un banner ha sido {$eventName}")
            ->dontSubmitEmptyLogs()
            ->logOnlyDirty()
            ->logAll();
    }
    public function image() {
        return $this->morphOne(Image::class, 'imageable');
    }
    public function moduleWeb() {
        return $this->belongsTo(ModuleWeb::class);
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
    public function videoPreview() {
        $video = asset('assets/admin/media/video/blank-video.mp4');
        if ($this->video) {
            if (Storage::exists($this->video)) {
                $video = Storage::url($this->video);
            } else {
                $video = $this->video;
            }
        }

        return $video;
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
        $banners = Banner::with(['image', 'moduleWeb'])->orderBy('order', 'asc')->get();
        Cache::put(self::CACHE_KEY, $banners);
    }
}
