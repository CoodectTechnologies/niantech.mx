<?php

namespace App\Models;

use Carbon\Carbon;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Translatable\HasTranslations;

class BlogCategory extends Model
{
    use HasFactory;
    use HasTranslations;
    use LogsActivity;
    use Sluggable;

    protected $guarded = [];
    public $translatable = ['name'];

    public function getActivitylogOptions(): LogOptions {
        return LogOptions::defaults()
            ->useLogName('Blog Categoría')
            ->setDescriptionForEvent(fn (string $eventName) => "Una categoría de blog ha sido {$eventName}")
            ->dontSubmitEmptyLogs()
            ->logOnlyDirty()
            ->logAll();
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
    public function blogPosts() {
        return $this->belongsToMany(BlogPost::class);
    }
    public function dateToString() {
        return Carbon::parse($this->created_at)->toFormattedDateString();
    }
}
