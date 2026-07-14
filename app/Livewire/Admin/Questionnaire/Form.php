<?php

namespace App\Livewire\Admin\Questionnaire;

use App\Models\Questionnaire;
use App\Traits\LivewireTranslatable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class Form extends Component
{
    use LivewireTranslatable;
    use WithFileUploads;

    public $method;
    public $questionnaire;
    public $imageTmp;
    public $questions = [];
    protected $listeners = ['render'];

    public function mount(Questionnaire $questionnaire, $method) {
        $this->questionnaire = $questionnaire;
        $this->method = $method;
        $this->loadTranslations($this->questionnaire);

        // Cargar preguntas existentes
        if ($this->questionnaire->exists) {
            foreach ($this->questionnaire->questions()->with('options')->orderBy('order')->get() as $question) {
                $options = [];
                foreach ($question->options()->orderBy('order')->get() as $option) {
                    $options[] = [
                        'id' => $option->id,
                        'text' => $option->getTranslation('option_text', translatable(), false) ?? '',
                        'is_positive' => (bool) $option->is_positive,
                    ];
                }

                $this->questions[] = [
                    'id' => $question->id,
                    'question' => $question->getTranslation('question', translatable(), false) ?? '',
                    'type' => $question->type,
                    'order' => $question->order,
                    'options' => $options,
                ];
            }
        }
    }
    protected function rules() {
        return [
            'translations.name.'.translatable() => 'required|unique_translation:questionnaires,name,'.$this->questionnaire->id,
            'translations.description.'.translatable() => 'nullable',
            'questionnaire.status' => 'required',
            'questionnaire.min_positive_percentage' => 'required|integer|min:0|max:100',
            'translations.meta_title.'.translatable() => 'nullable',
            'translations.meta_description.'.translatable() => 'nullable',
            'translations.meta_keywords.'.translatable() => 'nullable',
            'imageTmp' => $this->questionnaire->image ? 'image|nullable' : 'nullable',
            'questions.*.question' => 'required',
            'questions.*.type' => 'required|in:single,multiple',
            'questions.*.options.*.text' => 'required',
        ];
    }
    public function render() {
        return view('livewire.admin.questionnaire.form');
    }
    public function addQuestion() {
        $this->questions[] = [
            'id' => null,
            'question' => '',
            'type' => 'single',
            'order' => count($this->questions),
            'options' => [
                ['id' => null, 'text' => '', 'is_positive' => false],
            ],
        ];
    }
    public function addOption($questionIndex) {
        $this->questions[$questionIndex]['options'][] = [
            'id' => null,
            'text' => '',
            'is_positive' => false,
        ];
    }
    public function removeOption($questionIndex, $optionIndex) {
        unset($this->questions[$questionIndex]['options'][$optionIndex]);
        $this->questions[$questionIndex]['options'] = array_values($this->questions[$questionIndex]['options']);
    }
    public function removeQuestion($index) {
        unset($this->questions[$index]);
        $this->questions = array_values($this->questions);
    }
    public function store() {
        $this->validate();
        $this->questionnaire->user_id = Auth::id();
        $this->saveTranslations($this->questionnaire);
        $this->questionnaire->save();
        $this->saveImage();
        $this->saveQuestions();
        $this->regenerateCache();
        session()->flash('alert', __('Registration successfully added'));
        session()->flash('alert-type', 'success');

        return redirect()->route('admin.questionnaire.show', $this->questionnaire);
    }
    public function update() {
        $this->validate();
        $this->saveTranslations($this->questionnaire);
        $this->questionnaire->update();
        $this->saveImage();
        $this->saveQuestions();
        $this->regenerateCache();
        session()->flash('alert', __('Registration successfully updated'));
        session()->flash('alert-type', 'success');

        return redirect()->route('admin.questionnaire.show', $this->questionnaire);
    }
    public function saveImage() {
        if ($this->imageTmp) {
            $url = $this->imageTmp->store('questionnaire');
            imageManager($url, 1920, $this->questionnaire);
        }
    }
    public function removeImage() {
        if ($this->questionnaire->image) {
            if (Storage::exists($this->questionnaire->image->url)) {
                Storage::delete($this->questionnaire->image->url);
            }
            $this->questionnaire->image()->delete();
            $this->questionnaire->image = null;
        }
        $this->reset('imageTmp');
        $this->dispatch('alert', 'success', __('Image successfully deleted'));
    }
    public function saveQuestions() {
        // Eliminar preguntas que ya no existen
        $existingIds = array_filter(array_column($this->questions, 'id'));
        $this->questionnaire->questions()->whereNotIn('id', $existingIds)->delete();

        // Guardar o actualizar preguntas
        foreach ($this->questions as $index => $questionData) {
            $question = $this->questionnaire->questions()->updateOrCreate(
                ['id' => $questionData['id']],
                [
                    'question' => $questionData['question'],
                    'type' => $questionData['type'],
                    'order' => $index,
                ]
            );
            $question->setTranslation('question', translatable(), $questionData['question']);
            $question->save();

            // Eliminar opciones que ya no existen
            $existingOptionIds = array_filter(array_column($questionData['options'], 'id'));
            $question->options()->whereNotIn('id', $existingOptionIds)->delete();

            // Guardar opciones
            foreach ($questionData['options'] as $optionIndex => $optionData) {
                $option = $question->options()->updateOrCreate(
                    ['id' => $optionData['id']],
                    [
                        'option_text' => $optionData['text'],
                        'is_positive' => $optionData['is_positive'] ?? false,
                        'order' => $optionIndex,
                    ]
                );
                $option->setTranslation('option_text', translatable(), $optionData['text']);
                $option->save();
            }
        }
    }
    private function regenerateCache() {
        Questionnaire::regenerateCache();
    }
}
