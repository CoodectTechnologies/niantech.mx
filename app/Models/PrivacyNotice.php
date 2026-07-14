<?php

namespace App\Models;

use Carbon\Carbon;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Sitemap\Contracts\Sitemapable;
use Spatie\Sitemap\Tags\Url;
use Spatie\Translatable\HasTranslations;

class PrivacyNotice extends Model implements Sitemapable
{
    use HasFactory;
    use HasTranslations;
    use LogsActivity;
    use Sluggable;

    const CACHE_KEY = 'privacy-notices';

    protected $guarded = [];
    public $translatable = ['name', 'content'];

    public function getActivitylogOptions(): LogOptions {
        return LogOptions::defaults()
            ->useLogName('Politicas de privacidad')
            ->setDescriptionForEvent(fn (string $eventName) => "Una politica de privacidad sido  {$eventName}")
            ->dontSubmitEmptyLogs()
            ->logOnlyDirty()
            ->logAll();
    }
    public function toSitemapTag(): Url|string|array {
        return route('ecommerce.privacy-notice.show', $this);
    }
    public function getRouteKeyName() {
        return 'slug';
    }
    public function sluggable(): array {
        return [
            'slug' => [
                'source' => 'name',
            ],
        ];
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
        $privacyNotices = PrivacyNotice::orderBy('order', 'desc')->get();
        Cache::put(self::CACHE_KEY, $privacyNotices);
    }
    public function dateToString() {
        return Carbon::parse($this->created_at)->toFormattedDateString();
    }
    public function lastUpdatedToString() {
        return Carbon::parse($this->updated_at)->diffForHumans();
    }
}
