<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Address extends Model
{
    use HasFactory;
    use LogsActivity;

    protected $guarded = [];
    protected $casts = [
        'is_default' => 'boolean',
        'is_billing' => 'boolean',
        'is_billing_default' => 'boolean',
    ];

    public function getActivitylogOptions(): LogOptions {
        return LogOptions::defaults()
            ->useLogName('Dirección de envío')
            ->setDescriptionForEvent(fn (string $eventName) => "Una dirección de envío ha sido {$eventName}")
            ->dontSubmitEmptyLogs()
            ->logOnlyDirty()
            ->logAll();
    }
    public function useCfdi() {
        return $this->belongsTo(UseCfdi::class);
    }
    public function fiscalRegime() {
        return $this->belongsTo(FiscalRegime::class);
    }
    public function orders() {
        return $this->hasMany(Order::class);
    }
    public function user() {
        return $this->belongsTo(User::class);
    }
    public function country() {
        return $this->belongsTo(Country::class);
    }
    public function state() {
        return $this->belongsTo(State::class);
    }
    public function dateToString() {
        return Carbon::parse($this->created_at)->toFormattedDateString();
    }
    public function scopeValidate($query) {
        if (config('services.odoo.status')) {
            $query = $query->whereNotNull('provider_id');
        }

        return $query;
    }
}
