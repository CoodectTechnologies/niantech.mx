<?php

namespace App\Livewire\Admin\EmailWeb;

use App\Models\EmailWeb;
use Exception;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $perPage = 50;
    public $search;
    public $conversionFilter;
    protected $queryString = ['search'];
    protected $paginationTheme = 'bootstrap';
    protected $listeners = ['render'];

    public function updatingSearch() {
        $this->resetPage();
    }
    public function render() {
        $emailWebs = EmailWeb::query()->orderBy('id', 'desc');
        if ($this->search) {
            $emailWebs = $emailWebs->where('name', 'LIKE', "%{$this->search}%")
                ->orWhere('name', 'LIKE', "%{$this->search}%")
                ->orWhere('email', 'LIKE', "%{$this->search}%")
                ->orWhere('subject', 'LIKE', "%{$this->search}%");
        }
        if ($this->conversionFilter) {
            $emailWebs = $emailWebs->where('conversion', $this->conversionFilter);
        }
        $emailWebs = $emailWebs->paginate($this->perPage);

        return view('livewire.admin.email-web.index', compact('emailWebs'));
    }
    public function noAffiliated($id) {
        EmailWeb::where('id', $id)->update([
            'conversion' => 'No',
        ]);
        $this->dispatch('alert', 'error', 'No hubo conversión');
    }
    public function yesAffiliated($id) {
        EmailWeb::where('id', $id)->update([
            'conversion' => 'Si',
        ]);
        $this->dispatch('alert', 'success', 'Si hubo conversión');
    }
    public function wattingAffiliated($id) {
        EmailWeb::where('id', $id)->update([
            'conversion' => 'En espera',
        ]);
        $this->dispatch('alert', 'primary', 'En espera');
    }
    public function destroy(EmailWeb $emailWeb) {
        try {
            $emailWeb->delete();
            $this->dispatch('alert', 'success', __('Successful elimination'));
        } catch (Exception $e) {
            report($e);
            $this->dispatch('alert', 'error', $e->getMessage());
        }
    }
}
