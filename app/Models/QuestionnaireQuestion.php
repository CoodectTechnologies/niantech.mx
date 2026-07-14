<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Translatable\HasTranslations;

class QuestionnaireQuestion extends Model
{
    use HasFactory;
    use HasTranslations;
    use LogsActivity;

    protected $guarded = [];
    public $translatable = ['question'];

    public function getActivitylogOptions(): LogOptions {
        return LogOptions::defaults()
            ->useLogName('Questionnaire Questions')
            ->setDescriptionForEvent(fn (string $eventName) => "Una pregunta de cuestionario ha sido {$eventName}")
            ->dontSubmitEmptyLogs()
            ->logOnlyDirty()
            ->logAll();
    }
    public function questionnaire() {
        return $this->belongsTo(Questionnaire::class);
    }
    public function options() {
        return $this->hasMany(QuestionnaireQuestionOption::class)->orderBy('order');
    }
    public function answers() {
        return $this->hasMany(QuestionnaireAnswer::class);
    }
}
