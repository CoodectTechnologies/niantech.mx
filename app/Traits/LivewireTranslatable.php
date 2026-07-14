<?php

namespace App\Traits;

trait LivewireTranslatable
{
    public $translations = [];

    public function loadTranslations($model) {
        if (property_exists($model, 'translatable') && is_array($model->translatable)) {
            foreach ($model->translatable as $attribute) {
                $this->translations[$attribute][translatable()] = $model->getTranslation($attribute, translatable(), false) ?? null;
            }
        }
    }
    public function saveTranslations($model) {
        if (property_exists($model, 'translatable') && is_array($model->translatable)) {
            foreach ($model->translatable as $attribute) {
                if (isset($this->translations[$attribute][translatable()])) {
                    $model->setTranslation($attribute, translatable(), $this->translations[$attribute][translatable()] ?: '');
                }
            }
        }
    }
}
