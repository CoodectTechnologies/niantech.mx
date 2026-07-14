<?php

namespace App\Livewire\Admin\Coupon;

use App\Models\Coupon;
use Exception;
use Livewire\Component;

class Index extends Component
{
    protected $listeners = ['render'];

    public function render() {
        $coupons = Coupon::query()->with(['orders', 'currencies'])->orderBy('id', 'desc')->get();

        return view('livewire.admin.coupon.index', compact('coupons'));
    }
    public function destroy(Coupon $coupon) {
        try {
            $coupon->delete();
            $this->dispatch('alert', 'success', __('Successful elimination'));
        } catch (Exception $e) {
            report($e);
            $this->dispatch('alert', 'error', $e->getMessage());
        }
    }
}
