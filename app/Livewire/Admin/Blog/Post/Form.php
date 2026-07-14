<?php

namespace App\Livewire\Admin\Blog\Post;

use App\Models\BlogCategory;
use App\Models\BlogPost;
use App\Models\BlogTag;
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
    public $post;
    public $imageTmp;
    public $postCategoryArray = [];
    public $postTagArray = [];
    protected $listeners = ['render'];

    public function mount(BlogPost $post, $method) {
        $this->post = $post;
        $this->method = $method;
        $this->postCategoryArray = $this->post->blogCategories()->pluck('blog_category_id')->toArray();
        $this->postTagArray = $this->post->blogTags()->pluck('blog_tag_id')->toArray();
        $this->loadTranslations($this->post);
    }
    protected function rules() {
        return [
            'translations.name.'.translatable() => 'required|unique_translation:blog_posts,name,'.$this->post->id,
            'translations.fragment.'.translatable() => 'required',
            'translations.body.'.translatable() => 'required',
            'post.status' => 'required',
            'translations.meta_title.'.translatable() => 'nullable',
            'translations.meta_description.'.translatable() => 'nullable',
            'translations.meta_keywords.'.translatable() => 'nullable',
            'imageTmp' => $this->post->image ? 'image|nullable' : 'image|required',
        ];
    }
    public function render() {
        $blogCategories = BlogCategory::orderBy('id', 'desc')->get();
        $blogTags = BlogTag::orderBy('id', 'desc')->get();

        return view('livewire.admin.blog.post.form', compact('blogCategories', 'blogTags'));
    }
    public function store() {
        $this->validate();
        $this->post->user_id = Auth::id();
        $this->saveTranslations($this->post);
        $this->post->save();
        $this->saveImage();
        $this->saveCategories();
        $this->saveTags();
        $this->regenerateCache();
        session()->flash('alert', __('Registration successfully added'));
        session()->flash('alert-type', 'success');

        return redirect()->route('admin.blog.post.show', $this->post);
    }
    public function update() {
        $this->validate();
        $this->saveTranslations($this->post);
        $this->post->update();
        $this->saveImage();
        $this->saveCategories();
        $this->saveTags();
        $this->regenerateCache();
        session()->flash('alert', __('Registration successfully updated'));
        session()->flash('alert-type', 'success');

        return redirect()->route('admin.blog.post.show', $this->post);
    }
    public function saveImage() {
        if ($this->imageTmp) {
            $url = $this->imageTmp->store('blog/post');
            imageManager($url, 1920, $this->post);
        }
    }
    public function removeImage() {
        if ($this->post->image) {
            if (Storage::exists($this->post->image->url)) {
                Storage::delete($this->post->image->url);
            }
            $this->post->image()->delete();
            $this->post->image = null;
        }
        $this->reset('imageTmp');
        $this->dispatch('alert', 'success', __('Image successfully deleted'));
    }
    public function saveCategories() {
        $this->post->blogCategories()->sync($this->postCategoryArray);
    }
    public function saveTags() {
        $this->post->blogTags()->sync($this->postTagArray);
    }
    private function regenerateCache() {
        BlogPost::regenerateCache();
    }
}
