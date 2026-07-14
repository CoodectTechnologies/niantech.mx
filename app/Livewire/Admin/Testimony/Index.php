<?php

namespace App\Livewire\Admin\Testimony;

use App\Models\Testimony;
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
        $testimonies = Testimony::query()->with('image')->orderBy('id', 'desc');
        if ($this->search) {
            $testimonies = $testimonies->where('name', 'LIKE', "%{$this->search}%");
        }
        $testimonies = $testimonies->paginate($this->perPage);

        return view('livewire.admin.testimony.index', compact('testimonies'));
    }
    public function destroy(Testimony $testimony) {
        try {
            if ($testimony->image) {
                if (Storage::exists($testimony->image->url)) {
                    Storage::delete($testimony->image->url);
                }
                $testimony->image()->delete();
            }
            $testimony->delete();
            Testimony::regenerateCache();
            $this->dispatch('alert', 'success', __('Successful elimination'));
        } catch (Exception $e) {
            report($e);
            $this->dispatch('alert', 'error', $e->getMessage());
        }
    }
}
