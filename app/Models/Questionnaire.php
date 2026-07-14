<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Sitemap\Contracts\Sitemapable;
use Spatie\Sitemap\Tags\Url;
use Spatie\Translatable\HasTranslations;

class Questionnaire extends Model implements Sitemapable
{
    use HasFactory;
    use HasTranslations;
    use LogsActivity;
    use Sluggable;

    const CACHE_KEY = 'questionnaires';

    protected $guarded = [];
    public $translatable = ['name', 'description', 'meta_title', 'meta_description', 'meta_keywords'];

    public function getActivitylogOptions(): LogOptions {
        return LogOptions::defaults()
            ->useLogName('Questionnaires')
            ->setDescriptionForEvent(fn (string $eventName) => "Un cuestionario ha sido {$eventName}")
            ->dontSubmitEmptyLogs()
            ->logOnlyDirty()
            ->logAll();
    }
    public function toSitemapTag(): Url|string|array {
        return route('ecommerce.questionnaire.show', $this);
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
    public function questions() {
        return $this->hasMany(QuestionnaireQuestion::class)->orderBy('order');
    }
    public function responses() {
        return $this->hasMany(QuestionnaireResponse::class);
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
    public function scopeValidateQuestionnaire($query) {
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
        $questionnaires = self::with(['image', 'questions.options', 'responses'])->where('status', 'Publicado')->orderBy('created_at', 'desc')->get();
        Cache::put(self::CACHE_KEY, $questionnaires);
    }
}
