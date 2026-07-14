<?php

namespace App\Livewire\Admin\Service;

use App\Models\Service;
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
        $services = Service::with(['projects'])->orderBy('order');
        if ($this->search) {
            $services = $services->where('name', 'LIKE', "%{$this->search}%")
                ->orWhere('category', 'LIKE', "%{$this->search}%");
        }
        $services = $services->paginate($this->perPage);

        return view('livewire.admin.service.index', compact('services'));
    }
    public function destroy(Service $service) {
        try {
            if ($service->image) {
                if (Storage::exists($service->image->url)) {
                    Storage::delete($service->image->url);
                }
                $service->image()->delete();
            }
            $service->delete();
            Service::regenerateCache();
            $this->dispatch('alert', 'success', __('Successful elimination'));
        } catch (Exception $e) {
            report($e);
            $this->dispatch('alert', 'error', $e->getMessage());
        }
    }
}
