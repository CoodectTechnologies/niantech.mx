<?php

namespace App\Livewire\Admin\Blog\Category;

use App\Models\BlogCategory;
use App\Traits\LivewireTranslatable;
use Livewire\Component;

class Form extends Component
{
    use LivewireTranslatable;

    public $method;
    public $blogCategory;

    public function mount(BlogCategory $blogCategory, $method) {
        $this->blogCategory = $blogCategory;
        $this->method = $method;
        $this->loadTranslations($this->blogCategory);
    }
    protected function rules() {
        return [
            'translations.name.'.translatable() => 'required|unique_translation:blog_categories,name,'.$this->blogCategory->id,
        ];
    }
    public function render() {
        return view('livewire.admin.blog.category.form');
    }
    public function store() {
        $this->validate();
        $this->saveTranslations($this->blogCategory);
        $this->blogCategory->save();
        $this->blogCategory = new BlogCategory;
        $this->dispatch('alert', 'success', __('Registration successfully added'));
        $this->dispatch('render');
    }
    public function update() {
        $this->validate();
        $this->saveTranslations($this->blogCategory);
        $this->blogCategory->update();
        $this->dispatch('alert', 'success', __('Registration successfully updated'));
        $this->dispatch('render');
    }
}
