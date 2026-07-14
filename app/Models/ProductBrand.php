<?php

namespace App\Models;

use Carbon\Carbon;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class ProductBrand extends Model
{
    use HasFactory;
    use LogsActivity;
    use Sluggable;

    protected $guarded = [];

    public function getActivitylogOptions(): LogOptions {
        return LogOptions::defaults()
            ->useLogName('Producto marca')
            ->setDescriptionForEvent(fn (string $eventName) => "Una marca de producto ha sido  {$eventName}")
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
    public function image() {
        return $this->morphOne(Image::class, 'imageable');
    }
    public function products() {
        return $this->hasMany(Product::class);
    }
    public function promotions() {
        return $this->morphToMany(Promotion::class, 'promotionable')->withTimestamps();
    }
    public function coupons() {
        return $this->morphToMany(Coupon::class, 'couponable')->withTimestamps();
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
    public function dateToString() {
        return Carbon::parse($this->created_at)->toFormattedDateString();
    }
}
