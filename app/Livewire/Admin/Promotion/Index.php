<?php

namespace App\Livewire\Admin\Promotion;

use App\Models\Promotion;
use Exception;
use Livewire\Component;

class Index extends Component
{
    public $search;
    protected $queryString = ['search'];
    protected $listeners = ['render'];

    public function render() {
        $promotions = Promotion::query()->with(['products', 'currencies'])->orderBy('id', 'desc');
        if ($this->search) {
            $promotions = $promotions->where('name', 'LIKE', "%{$this->search}%");
        }
        $promotions = $promotions->get();

        return view('livewire.admin.promotion.index', compact('promotions'));
    }
    public function destroy(Promotion $promotion) {
        try {
            $promotion->delete();
            Promotion::regenerateCache();
            $this->dispatch('alert', 'success', __('Successful elimination'));
        } catch (Exception $e) {
            report($e);
            $this->dispatch('alert', 'error', $e->getMessage());
        }
    }
}
