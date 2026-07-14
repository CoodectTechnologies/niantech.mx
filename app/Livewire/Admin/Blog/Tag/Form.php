<?php

namespace App\Livewire\Admin\Blog\Tag;

use App\Models\BlogTag;
use App\Traits\LivewireTranslatable;
use Livewire\Component;

class Form extends Component
{
    use LivewireTranslatable;

    public $method;
    public $blogTag;

    public function mount(BlogTag $blogTag, $method) {
        $this->blogTag = $blogTag;
        $this->method = $method;
        $this->loadTranslations($this->blogTag);
    }
    protected function rules() {
        return [
            'translations.name.'.translatable() => 'required',
        ];
    }
    public function render() {
        return view('livewire.admin.blog.tag.form');
    }
    public function store() {
        $this->validate();
        $this->saveTranslations($this->blogTag);
        $this->blogTag->save();
        $this->blogTag = new BlogTag;
        $this->dispatch('alert', 'success', __('Registration successfully added'));
        $this->dispatch('render');
    }
    public function update() {
        $this->validate();
        $this->saveTranslations($this->blogTag);
        $this->blogTag->update();
        $this->dispatch('alert', 'success', __('Registration successfully updated'));
        $this->dispatch('render');
    }
}
