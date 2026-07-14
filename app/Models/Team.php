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

class Team extends Model
{
    use HasFactory;
    use HasTranslations;
    use LogsActivity;

    const CACHE_KEY = 'team';

    protected $guarded = [];
    public $translatable = ['biography', 'position'];

    public function getActivitylogOptions(): LogOptions {
        return LogOptions::defaults()
            ->useLogName('Team')
            ->setDescriptionForEvent(fn (string $eventName) => "Un registro de team ha sido {$eventName}")
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
        $team = Team::with(['image'])->get();
        Cache::put(self::CACHE_KEY, $team);
    }
}
