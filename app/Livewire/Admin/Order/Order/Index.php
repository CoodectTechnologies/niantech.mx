<?php

namespace App\Livewire\Admin\Order\Order;

use App\Models\Order;
use App\Models\User;
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
    public $user;
    public $status;
    public $paymentStatus;

    public function mount($user = null) {
        if ($user) {
            $this->user = User::find($user->id);
        }
    }
    public function updatingSearch() {
        $this->resetPage();
    }
    public function render() {
        if ($this->user) {
            $orders = $this->user->orders()->with('user')->orderBy('id', 'desc');
        } else {
            $orders = Order::query()->with('user')->orderBy('id', 'desc');
        }
        if ($this->search) {
            $orders = $orders->where('number', 'LIKE', "%{$this->search}%")
                ->orWhereRelation('address', 'email', 'LIKE', "%{$this->search}%")
                ->orWhereRelation('address', 'name', 'LIKE', "%{$this->search}%");
        }
        if ($this->status) {
            $orders = $orders->where('status', $this->status);
        }
        if ($this->paymentStatus) {
            $orders = $orders->where('payment_status', $this->paymentStatus);
        }
        $orders = $orders->paginate($this->perPage);

        return view('livewire.admin.order.order.index', compact('orders'));
    }
    public function placeholder(array $params = []) {
        return view('admin.components.skeletons.general', $params);
    }
    public function destroy(Order $order) {
        try {
            $order->delete();
            $this->dispatch('alert', 'success', __('Successful elimination'));
        } catch (Exception $e) {
            report($e);
            $this->dispatch('alert', 'error', $e->getMessage());
        }
    }
}
