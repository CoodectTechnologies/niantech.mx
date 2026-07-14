<?php

namespace App\Livewire\Admin\Team;

use App\Models\Team;
use App\Traits\LivewireTranslatable;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class Form extends Component
{
    use LivewireTranslatable;
    use WithFileUploads;

    public $person;
    public $method;
    public $imageTmp;

    // Tools
    public $order;

    protected function rules() {
        return [
            'person.order' => 'required',
            'person.name' => 'required',
            'translations.biography.'.translatable() => 'required',
            'translations.position.'.translatable() => 'nullable',
            'person.facebook' => 'nullable',
            'person.twitter' => 'nullable',
            'person.linkedin' => 'nullable',
            'person.instagram' => 'nullable',
            'person.whatsapp' => 'nullable',
            'imageTmp' => $this->person->image ? 'image|nullable' : 'image|required',
        ];
    }
    public function mount(Team $person, $method) {
        $this->person = $person;
        $this->method = $method;
        $this->order = $person->order;
        $this->loadTranslations($this->person);
    }
    public function render() {
        $this->loadLastOrder();

        return view('livewire.admin.team.form');
    }
    public function store() {
        $this->validate();
        $this->reOrder();
        $this->saveTranslations($this->person);
        $this->person->save();
        $this->saveImage();
        $this->regenerateCache();
        $this->person = new Team;
        $this->reset('imageTmp');
        $this->dispatch('alert', 'success', __('Registration successfully added'));
        $this->dispatch('render');
    }
    public function update() {
        $this->validate();
        $this->reOrder();
        $this->saveTranslations($this->person);
        $this->person->update();
        $this->saveImage();
        $this->regenerateCache();
        $this->dispatch('alert', 'success', __('Registration successfully updated'));
        $this->dispatch('render');
    }
    public function saveImage() {
        if ($this->imageTmp) {
            $url = $this->imageTmp->store('team');
            imageManager($url, 300, $this->person);
        }
    }
    public function removeImage() {
        if ($this->person->image) {
            if (Storage::exists($this->person->image->url)) {
                Storage::delete($this->person->image->url);
            }
            $this->person->image()->delete();
            $this->person->image = null;
        }
        $this->reset('imageTmp');
        $this->dispatch('alert', 'success', __('Image successfully deleted'));
    }
    private function reOrder() {
        if ($this->order != $this->person->order) {
            $teamToOrder = Team::where('order', '>=', $this->person->order)->get();
            foreach ($teamToOrder as $personToOrder) {
                $personToOrder->order = $personToOrder->order + 1;
                $personToOrder->update();
            }
        }
    }
    private function loadLastOrder() {
        if (! $this->person->order) {
            $lastOrder = Team::latest('order')->first();
            if ($lastOrder) {
                $this->person->order = ($lastOrder->order + 1);
            } else {
                $this->person->order = 1;
            }
        }
    }
    private function regenerateCache() {
        Team::regenerateCache();
    }
}
