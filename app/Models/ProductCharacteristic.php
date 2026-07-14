<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Mavinoo\Batch\Traits\HasBatch;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Translatable\HasTranslations;

class ProductCharacteristic extends Model
{
    use HasBatch;
    use HasFactory;
    use HasTranslations;
    use LogsActivity;

    protected $guarded = [];
    public $translatable = ['key', 'value'];

    public function getActivitylogOptions(): LogOptions {
        return LogOptions::defaults()
            ->useLogName('Producto característica')
            ->setDescriptionForEvent(fn (string $eventName) => "Una característica de producto ha sido  {$eventName}")
            ->dontSubmitEmptyLogs()
            ->logOnlyDirty()
            ->logAll();
    }
    public function product() {
        return $this->belongsTo(Product::class);
    }
}
