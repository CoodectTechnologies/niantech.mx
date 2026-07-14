<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Translatable\HasTranslations;

class QuestionnaireQuestionOption extends Model
{
    use HasFactory;
    use HasTranslations;
    use LogsActivity;

    protected $guarded = [];
    public $translatable = ['option_text'];
    protected $casts = [
        'is_positive' => 'boolean',
    ];

    public function getActivitylogOptions(): LogOptions {
        return LogOptions::defaults()
            ->useLogName('Questionnaire Question Options')
            ->setDescriptionForEvent(fn (string $eventName) => "Una opción de pregunta ha sido {$eventName}")
            ->dontSubmitEmptyLogs()
            ->logOnlyDirty()
            ->logAll();
    }
    public function question() {
        return $this->belongsTo(QuestionnaireQuestion::class, 'questionnaire_question_id');
    }
    public function answers() {
        return $this->hasMany(QuestionnaireAnswer::class);
    }
}
