<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Translatable\HasTranslations;

class QuestionAnswer extends Model
{
    use HasFactory;
    use HasTranslations;
    use LogsActivity;

    const CACHE_KEY = 'faqs';

    protected $guarded = [];
    public $translatable = ['question', 'answer'];

    public function getActivitylogOptions(): LogOptions {
        return LogOptions::defaults()
            ->useLogName('Pregunta y respuesta')
            ->setDescriptionForEvent(fn (string $eventName) => "Un pregunta y respuesta ha sido {$eventName}")
            ->dontSubmitEmptyLogs()
            ->logOnlyDirty()
            ->logAll();
    }
    public function getDescriptionForEvent(string $eventName): string {
        return "Un pregunta y respuesta ha sido {$eventName}";
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
        $faqs = self::query()->orderBy('created_at', 'desc')->get();
        Cache::put(self::CACHE_KEY, $faqs);
    }
}
