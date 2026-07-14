<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class TagAnalytic extends Model
{
    use HasFactory;

    const CACHE_KEY = 'tag-analytic';

    protected $guarded = [];

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
        $tagAnalytic = TagAnalytic::first() ?? new TagAnalytic;
        Cache::put(self::CACHE_KEY, $tagAnalytic);
    }
}
