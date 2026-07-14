<?php

namespace App\Livewire\Ecommerce\Blog;

use App\Models\BlogPost;
use Illuminate\Http\Request;
use Livewire\Component;

class Index extends Component
{
    public $search;
    public $tag;
    public $category;

    public function mount(Request $request) {
        if ($request->search) {
            $this->search = $request->search;
        }
        if ($request->tag) {
            $this->tag = $request->tag;
        }
        if ($request->category) {
            $this->category = $request->category;
        }
    }
    public function render() {
        $posts = BlogPost::with('blogTags', 'blogCategories', 'user')->orderBy('id', 'desc');
        $posts = $this->filters($posts);
        $posts = $posts->paginate(20);

        return view('livewire.ecommerce.blog.index', compact('posts'));
    }
    private function filters($posts) {
        if ($this->search) {
            $posts = $posts->where('name', 'LIKE', "%{$this->search}%");
        }
        if ($this->tag) {
            $posts = $posts->whereRelation('blogTags', 'slug', 'LIKE', "%{$this->tag}%");
        }
        if ($this->category) {
            $posts = $posts->whereRelation('blogCategories', 'slug', 'LIKE', "%{$this->category}%");
        }

        return $posts;
    }
}
