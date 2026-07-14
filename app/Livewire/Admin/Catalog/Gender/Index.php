<?php

namespace App\Livewire\Admin\Catalog\Gender;

use App\Models\ProductGender;
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
        $genders = ProductGender::query()->with('products')->orderBy('id', 'desc');
        if ($this->search) {
            $genders = $genders->where('name', 'LIKE', "%{$this->search}%");
        }
        $genders = $genders->paginate($this->perPage);

        return view('livewire.admin.catalog.gender.index', compact('genders'));
    }
    public function destroy(ProductGender $gender) {
        try {
            $gender->delete();
            $this->dispatch('alert', 'success', __('Successful elimination'));
        } catch (Exception $e) {
            report($e);
            $this->dispatch('alert', 'error', $e->getMessage());
        }
    }
}
