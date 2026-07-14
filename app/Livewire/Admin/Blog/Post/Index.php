<?php

namespace App\Livewire\Admin\Blog\Post;

use App\Models\BlogPost;
use Exception;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $perPage = 50;
    public $search;
    protected $queryString = ['search'];
    protected $paginationTheme = 'bootstrap';
    protected $listeners = ['render'];

    public function updatingSearch() {
        $this->resetPage();
    }
    public function render() {
        $posts = BlogPost::with(['user.image', 'comments', 'image', 'blogCategories'])->orderBy('id', 'desc');
        if ($this->search) {
            $posts = $posts->where('name', 'LIKE', "%{$this->search}%");
        }
        $posts = $posts->paginate($this->perPage);

        return view('livewire.admin.blog.post.index', compact('posts'));
    }
    public function destroy(BlogPost $post) {
        try {
            if ($post->image) {
                if (Storage::exists($post->image->url)) {
                    Storage::delete($post->image->url);
                }
                $post->image()->delete();
            }
            $post->delete();
            $this->dispatch('alert', 'success', __('Successful elimination'));
            BlogPost::regenerateCache();
        } catch (Exception $e) {
            report($e);
            $this->dispatch('alert', 'error', $e->getMessage());
        }
    }
}
