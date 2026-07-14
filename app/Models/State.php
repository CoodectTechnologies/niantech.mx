<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Mavinoo\Batch\Traits\HasBatch;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class State extends Model
{
    use HasBatch;
    use HasFactory;
    use LogsActivity;

    protected $guarded = [];

    public function getActivitylogOptions(): LogOptions {
        return LogOptions::defaults()
            ->useLogName('Estado')
            ->setDescriptionForEvent(fn (string $eventName) => "Un estado ha sido {$eventName}")
            ->dontSubmitEmptyLogs()
            ->logOnlyDirty()
            ->logAll();
    }
    public function country() {
        return $this->belongsTo(Country::class);
    }
    public function shippingZones() {
        return $this->belongsToMany(ShippingZone::class);
    }
    public function productWarehouses() {
        return $this->belongsToMany(ProductWarehouse::class)->withPivot('priority');
    }
    public function dateToString() {
        return Carbon::parse($this->created_at)->toFormattedDateString();
    }
    public function scopeValidate($query) {
        if (config('services.odoo.status')) {
            return $query->whereNotNull('provider_id')->where('provider_id', '<>', '');
        }
    }
}
