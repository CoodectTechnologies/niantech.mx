<?php

namespace App\Livewire\Admin\Blog\Category;

use App\Models\BlogCategory;
use Exception;
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
        $blogCategories = BlogCategory::with('blogPosts')->orderBy('id', 'desc');
        if ($this->search) {
            $blogCategories = $blogCategories->where('name', 'LIKE', "%{$this->search}%");
        }
        $blogCategories = $blogCategories->get();

        return view('livewire.admin.blog.category.index', compact('blogCategories'));
    }
    public function destroy(BlogCategory $blogCategory) {
        try {
            $blogCategory->delete();
            $this->dispatch('alert', 'success', __('Successful elimination'));
        } catch (Exception $e) {
            report($e);
            $this->dispatch('alert', 'error', $e->getMessage());
        }
    }
}
