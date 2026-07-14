<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class QuestionnaireResponse extends Model
{
    use HasFactory;
    use LogsActivity;

    protected $guarded = [];

    public function getActivitylogOptions(): LogOptions {
        return LogOptions::defaults()
            ->useLogName('Questionnaire Responses')
            ->setDescriptionForEvent(fn (string $eventName) => "Una respuesta de cuestionario ha sido {$eventName}")
            ->dontSubmitEmptyLogs()
            ->logOnlyDirty()
            ->logAll();
    }
    public function questionnaire() {
        return $this->belongsTo(Questionnaire::class);
    }
    public function answers() {
        return $this->hasMany(QuestionnaireAnswer::class);
    }
    public function scopeApt($query) {
        return $query->where('is_apt', true);
    }
    public function scopeSearch($query, $search) {
        if ($search) {
            return $query->where(function ($query) use ($search) {
                $query->where('name', 'like', '%'.$search.'%')
                    ->orWhere('email', 'like', '%'.$search.'%')
                    ->orWhere('phone', 'like', '%'.$search.'%');
            });
        }
    }
}
