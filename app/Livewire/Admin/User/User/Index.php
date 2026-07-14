<?php

namespace App\Livewire\Admin\User\User;

use App\Models\User;
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
        $users = User::query()->with(['roles', 'image'])->orderBy('id', 'desc');
        if ($this->search) {
            $users = $users->where('name', 'LIKE', "%{$this->search}%")->orWhere('email', 'LIKE', "%{$this->search}%");
        }
        $users = $users->paginate($this->perPage);

        return view('livewire.admin.user.user.index', compact('users'));
    }
    public function destroy(User $user) {
        try {
            if ($user->image) {
                if (Storage::exists($user->image->url)) {
                    Storage::delete($user->image->url);
                }
                $user->image()->delete();
            }
            $user->delete();
            $this->dispatch('alert', 'success', __('Successful elimination'));
        } catch (Exception $e) {
            report($e);
            $this->dispatch('alert', 'error', $e->getMessage());
        }
    }
}
