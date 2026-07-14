<?php

namespace App\Livewire\Ecommerce\Account\Profile;

use App\Exceptions\OdooException;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Form extends Component
{
    public $user;

    protected function rules() {
        return [
            'user.name' => 'required',
            'user.email' => 'required|email|unique:users,email,'.$this->user->id,
        ];
    }
    public function mount() {
        $this->user = User::find(Auth::id());
    }
    public function render() {
        return view('livewire.ecommerce.account.profile.form');
    }
    public function update() {
        $this->validate();
        try {
            $this->user->save();
            $this->dispatch('alert', 'success', __('Registration successfully updated'));

            return;
        } catch (OdooException $e) {
            report($e);
            $this->dispatch('alert', 'warning', __('Oops, an error occurred'), $e->getMessage());

            return;
        } catch (Exception $e) {
            report($e);
            $this->dispatch('alert', 'warning', __('Oops, an error occurred'), __('There was an error saving your registration, please try again later.'));

            return;
        }
    }
}
