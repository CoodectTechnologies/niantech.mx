<?php

namespace App\Livewire\Admin\Portfolio;

use App\Models\Portfolio;
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
        $portfolio = Portfolio::with(['service'])->orderBy('id', 'desc');
        if ($this->search) {
            $portfolio = $portfolio->where('name', 'LIKE', "%{$this->search}%");
        }
        $portfolio = $portfolio->paginate($this->perPage);

        return view('livewire.admin.portfolio.index', compact('portfolio'));
    }
    public function destroy(Portfolio $project) {
        try {
            if ($project->image) {
                if (Storage::exists($project->image->url)) {
                    Storage::delete($project->image->url);
                }
                $project->image()->delete();
            }
            if (count($project->images)) {
                foreach ($project->images as $img) {
                    if (Storage::exists($img->url)) {
                        Storage::delete($img->url);
                    }
                }
                $project->images()->delete();
            }
            Portfolio::regenerateCache();
            $project->delete();
            $this->dispatch('alert', 'success', __('Successful elimination'));
        } catch (Exception $e) {
            report($e);
            $this->dispatch('alert', 'error', $e->getMessage());
        }
    }
}
