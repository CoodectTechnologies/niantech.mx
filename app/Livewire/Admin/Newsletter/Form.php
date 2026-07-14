<?php

namespace App\Livewire\Admin\Newsletter;

use App\Models\Newsletter;
use Livewire\Component;

class Form extends Component
{
    public $newsletter;
    public $method;
    public $imageTmp;

    protected function rules() {
        return [
            'newsletter.email' => 'required|email|unique:newsletters,email,'.$this->newsletter->id,
        ];
    }
    public function mount(Newsletter $newsletter, $method) {
        $this->newsletter = $newsletter;
        $this->method = $method;
    }
    public function render() {
        return view('livewire.admin.newsletter.form');
    }
    public function store() {
        $this->validate();
        $this->newsletter->save();
        $this->newsletter = new Newsletter;
        $this->dispatch('alert', 'success', __('Registration successfully added'));
        $this->dispatch('render');
    }
    public function update() {
        $this->validate();
        $this->newsletter->update();
        $this->dispatch('alert', 'success', __('Registration successfully updated'));
        $this->dispatch('render');
    }
}
