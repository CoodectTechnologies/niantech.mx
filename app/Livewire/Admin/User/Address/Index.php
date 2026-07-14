<?php

namespace App\Livewire\Admin\User\Address;

use App\Exceptions\OdooException;
use App\Models\Address;
use App\Models\User;
use Exception;
use Livewire\Component;

class Index extends Component
{
    protected $listeners = ['render'];
    public $user;

    public function mount(User $user) {
        $this->user = $user;
    }
    public function render() {
        $addresses = $this->user->addresses()->with(['orders', 'state.country'])->orderBy('id', 'desc')->get();

        return view('livewire.admin.user.address.index', compact('addresses'));
    }
    public function placeholder(array $params = []) {
        return view('admin.components.skeletons.general', $params);
    }
    public function destroy(Address $address) {
        try {
            if (! count($address->orders)) {
                $address->delete();
                $this->dispatch('alert', 'success', __('Successful elimination'));
            } else {
                $this->dispatch('alert', 'warning', 'No puedes eliminar esta dirección por estar relacionada a una orden.');
            }
        } catch (OdooException $e) {
            report($e);
            $this->dispatch('alert', 'error', $e->getMessage());
        } catch (Exception $e) {
            report($e);
            $this->dispatch('alert', 'error', __('Ocurrio un error, reintente más tarde'));
        }
    }
}
