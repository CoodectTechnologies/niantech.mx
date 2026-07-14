<?php

namespace App\Livewire\Admin\Order\Provider;

use App\Models\OrderProvider;
use App\Services\Synchronizers\Order\GuideController;
use Livewire\Component;
use Livewire\WithFileUploads;

class Guide extends Component
{
    use WithFileUploads;

    public $orderProvider;
    public $providerGuideTMP;

    public function rules() {
        return [
            'providerGuideTMP' => $this->orderProvider->provider_guide ? 'nullable' : 'required',
        ];
    }
    public function mount(OrderProvider $orderProvider) {
        $this->orderProvider = $orderProvider;
    }
    public function render() {
        return view('livewire.admin.order.provider.guide');
    }
    public function create() {
        $this->validate();
        if ($this->providerGuideTMP) {
            $url = $this->providerGuideTMP->store('order/provider/'.$this->orderProvider->provider_id.'/guide');
            $this->orderProvider->provider_guide = $url;
            $this->orderProvider->update();
            GuideController::create($this->orderProvider);
            $this->dispatch('render')->to('admin.order.provider.index');
        }
    }
}
