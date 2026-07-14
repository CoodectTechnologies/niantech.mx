<?php

namespace App\Livewire\Admin\Subscription\Subscription;

use App\Models\Subscription;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    protected $queryString = ['filterSearch' => ['except' => '']];
    protected $listeners = ['render'];
    public $filterSearch;

    public function mount() {}
    public function render() {
        $subscriptions = Subscription::query()->with('user')->orderBy('id', 'desc');
        $subscriptions = $this->filters($subscriptions);
        $subscriptions = $subscriptions->paginate();

        return view('livewire.admin.subscription.subscription.index', compact('subscriptions'));
    }
    public function placeholder(array $params = []) {
        return view('admin.components.skeletons.general', $params);
    }
    private function filters($subscriptions) {
        if ($this->filterSearch) {
            $subscriptions = $subscriptions->where('type', 'LIKE', "%{$this->filterSearch}%")
                ->orWhere('stripe_id', 'LIKE', "%{$this->filterSearch}%")
                ->orWhere('stripe_status', 'LIKE', "%{$this->filterSearch}%")
                ->orWhere('stripe_price', 'LIKE', "%{$this->filterSearch}%")
                ->orWhereHas('user', function ($query) {
                    $query->where('email', 'LIKE', "%{$this->filterSearch}%")
                        ->orWhere('name', 'LIKE', "%{$this->filterSearch}%")
                        ->orWhere('mercadopago_id', 'LIKE', "%{$this->filterSearch}%");
                });
        }

        return $subscriptions;
    }
    public function updatingFilterSearch() {
        $this->resetPage();
    }
}
