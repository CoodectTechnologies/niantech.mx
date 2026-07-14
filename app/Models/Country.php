<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Mavinoo\Batch\Traits\HasBatch;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Country extends Model
{
    use HasBatch;
    use HasFactory;
    use LogsActivity;

    protected $guarded = [];

    public function getActivitylogOptions(): LogOptions {
        return LogOptions::defaults()
            ->useLogName('País')
            ->setDescriptionForEvent(fn (string $eventName) => "Un país ha sido {$eventName}")
            ->dontSubmitEmptyLogs()
            ->logOnlyDirty()
            ->logAll();
    }
    public function states() {
        return $this->hasMany(State::class);
    }
    public function scopeValidate($query) {
        $query = $query->where('status', true);
        if (config('services.odoo.status')) {
            $query = $query->whereNotNull('provider_id')->where('provider_id', '<>', '');
        }

        return $query;
    }
}
