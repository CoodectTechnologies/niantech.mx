<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class QuestionnaireAnswer extends Model
{
    use HasFactory;
    use LogsActivity;

    protected $guarded = [];

    public function getActivitylogOptions(): LogOptions {
        return LogOptions::defaults()
            ->useLogName('Questionnaire Answers')
            ->setDescriptionForEvent(fn (string $eventName) => "Una respuesta específica ha sido {$eventName}")
            ->dontSubmitEmptyLogs()
            ->logOnlyDirty()
            ->logAll();
    }
    public function response() {
        return $this->belongsTo(QuestionnaireResponse::class, 'questionnaire_response_id');
    }
    public function question() {
        return $this->belongsTo(QuestionnaireQuestion::class, 'questionnaire_question_id');
    }
    public function option() {
        return $this->belongsTo(QuestionnaireQuestionOption::class, 'questionnaire_question_option_id');
    }
}
