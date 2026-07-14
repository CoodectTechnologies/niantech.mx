<?php

namespace App\Livewire\Ecommerce\Account\Address;

use App\Exceptions\OdooException;
use App\Models\Address;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Livewire\Component;

class Index extends Component
{
    public User $user;

    public function mount() {
        $this->user = User::find(Auth::id());
    }
    public function render() {
        $addresses = $this->user->addresses()->withCount('orders')->validate()->get();

        return view('livewire.ecommerce.account.address.index', compact('addresses'));
    }
    public function destroy(int $id) {
        try {
            $address = Address::withCount('orders')->where('id', $id)->where('user_id', $this->user->id)->first();
            if (! $address) {
                return;
            }
            if (! $address->orders_count) {
                $address->delete();
                $this->dispatch('alert', 'success', __('Successful elimination'));
            } else {
                $this->dispatch('alert', 'warning', __('You cannot delete this address because it is linked to an order.'));
            }
        } catch (OdooException $e) {
            report($e);
            Session::flash('alert', $e->getMessage());
            Session::flash('alert-type', 'warning');
        } catch (Exception $e) {
            report($e);
            Session::flash('alert', __('Ocurrio un error, reintente más tarde'));
            Session::flash('alert-type', 'warning');
        }
    }
}
