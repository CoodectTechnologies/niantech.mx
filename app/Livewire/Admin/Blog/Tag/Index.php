<?php

namespace App\Livewire\Admin\Blog\Tag;

use App\Models\BlogTag;
use Exception;
use Livewire\Component;

class Index extends Component
{
    protected $listeners = ['render'];

    public function render() {
        $blogTags = BlogTag::with('blogPosts')->orderBy('id', 'desc')->get();

        return view('livewire.admin.blog.tag.index', compact('blogTags'));
    }
    public function destroy(BlogTag $blogTag) {
        try {
            $blogTag->delete();
            $this->dispatch('alert', 'success', __('Successful elimination'));
        } catch (Exception $e) {
            report($e);
            $this->dispatch('alert', 'error', $e->getMessage());
        }
    }
}
