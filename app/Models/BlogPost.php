<?php

namespace App\Models;

use Carbon\Carbon;
use Cviebrock\EloquentSluggable\Sluggable;
use CyrildeWit\EloquentViewable\Contracts\Viewable;
use CyrildeWit\EloquentViewable\InteractsWithViews;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Sitemap\Contracts\Sitemapable;
use Spatie\Sitemap\Tags\Url;
use Spatie\Translatable\HasTranslations;

class BlogPost extends Model implements Sitemapable, Viewable
{
    use HasFactory;
    use HasTranslations;
    use InteractsWithViews;
    use LogsActivity;
    use Sluggable;

    const CACHE_KEY = 'blog_posts';

    protected $guarded = [];
    protected $removeViewsOnDelete = true;
    public $translatable = ['name', 'fragment', 'body', 'meta_title', 'meta_description', 'meta_keywords'];

    public function getActivitylogOptions(): LogOptions {
        return LogOptions::defaults()
            ->useLogName('Blog Posts')
            ->setDescriptionForEvent(fn (string $eventName) => "Un post de blog ha sido {$eventName}")
            ->dontSubmitEmptyLogs()
            ->logOnlyDirty()
            ->logAll();
    }
    public function toSitemapTag(): Url|string|array {
        return route('ecommerce.blog.show', $this);
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
    public function image() {
        return $this->morphOne(Image::class, 'imageable');
    }
    public function user() {
        return $this->belongsTo(User::class);
    }
    public function blogCategories() {
        return $this->belongsToMany(BlogCategory::class);
    }
    public function blogTags() {
        return $this->belongsToMany(BlogTag::class);
    }
    public function comments() {
        return $this->morphMany(Comment::class, 'commentable');
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
    public function getStarsAVG() {
        $starsAVG = 0;
        $commentStars = $this->comments()->validate()->sum('stars');
        $commentCounts = $this->comments()->validate()->count();
        if ($commentStars && $commentCounts) {
            $starsAVG = number_format(($commentStars / $commentCounts), 1);
        }

        return $starsAVG;
    }
    public function getStarsPercentageAVG() {
        $getStarsAVG = $this->getStarsAVG();

        return ($getStarsAVG * 100) / 5;
    }
    public function getStarsPercentage($qty) {
        $starsPercentage = 0;
        $commentsTotal = $this->comments()->validate()->count();
        $commentCounts = $this->comments()->where('stars', $qty)->validate()->count();
        if ($commentCounts) {
            $starsPercentage = ($commentCounts * 100) / $commentsTotal;
        }

        return floor($starsPercentage);
    }
    public function wasCommented() {
        $wasCommented = false;
        if (Auth::check()) {
            $wasCommented = $this->comments()->where('user_id', Auth::id())->first() ? true : false;
        }

        return $wasCommented;
    }
    public function viewUniques() {
        return views($this)->unique()->count();
    }
    public function dateToString() {
        return Carbon::parse($this->created_at)->toFormattedDateString();
    }
    public function scopeValidatePost($query) {
        return $query->where('status', 'Publicado');
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
        $blogPosts = self::with(['image', 'blogCategories', 'blogTags'])->orderBy('created_at', 'desc')->get();
        Cache::put(self::CACHE_KEY, $blogPosts);
    }
}
