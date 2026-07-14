<?php

namespace App\Livewire\Admin\Team;

use App\Models\Team;
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
        $team = Team::query()->orderBy('id', 'desc');
        if ($this->search) {
            $team = $team->where('name', 'LIKE', "%{$this->search}%")->orWhere('position', 'LIKE', "%{$this->search}%");
        }
        $team = $team->paginate($this->perPage);

        return view('livewire.admin.team.index', compact('team'));
    }
    public function destroy(Team $person) {
        try {
            if ($person->image) {
                if (Storage::exists($person->image->url)) {
                    Storage::delete($person->image->url);
                }
                $person->image()->delete();
            }
            $person->delete();
            Team::regenerateCache();
            $this->dispatch('alert', 'success', __('Successful elimination'));
        } catch (Exception $e) {
            report($e);
            $this->dispatch('alert', 'error', $e->getMessage());
        }
    }
}
