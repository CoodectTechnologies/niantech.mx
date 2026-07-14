<?php

namespace App\Livewire\Admin\User\Address;

use App\Exceptions\OdooException;
use App\Models\Address;
use App\Models\Country;
use App\Models\FiscalRegime;
use App\Models\State;
use App\Models\UseCfdi;
use App\Models\User;
use App\Rules\ValidRFC;
use Exception;
use Livewire\Component;

class Form extends Component
{
    public $user;
    public $address;
    public $method;
    public $countryId;
    public $countryCode;
    public $countries = [];
    public $states = [];
    public $useCfdis = [];
    public $fiscalRegimes = [];
    public $location = [];

    protected function rules() {
        return [
            'address.state_id' => 'required',
            'address.use_cfdi_id' => (($this->address->is_billing ?? false) && $this->countryCode == 'MX') ? 'required' : 'nullable',
            'address.fiscal_regime_id' => (($this->address->is_billing ?? false) && $this->countryCode == 'MX') ? 'required' : 'nullable',
            'address.provider' => 'nullable',
            'address.provider_id' => 'nullable',
            'address.vat' => (($this->address->is_billing ?? false) && $this->countryCode == 'MX') ? ['required', new ValidRFC] : ['nullable', new ValidRFC],
            'address.municipality' => 'required',
            'address.colony' => 'required',
            'address.zip_code' => 'required|min:5',
            'address.street' => 'required',
            'address.street_between' => 'nullable',
            'address.street_references' => 'nullable',
            'address.company' => 'nullable',
            'address.name' => 'required',
            'address.phone' => 'nullable',
            'address.email' => 'required|email|unique:users,email,'.($this->user->id ?? 'null'),
            'address.is_billing' => 'nullable|boolean',
            'address.is_billing_default' => 'nullable|boolean',
            'address.is_default' => 'nullable|boolean',
        ];
    }
    public function mount(User $user, Address $address, $method) {
        $this->user = $user;
        $this->address = $address;
        $this->address->load('state.country');
        $this->method = $method;
        $this->loadCountry();
        $this->loadCountries();
        $this->loadStates();
        $this->loadUseCfdis();
        $this->loadRegimes();
    }
    public function render() {
        return view('livewire.admin.user.address.form');
    }
    public function placeholder(array $params = []) {
        return view('admin.components.skeletons.general', $params);
    }
    public function store() {
        $this->validate();
        try {
            $this->address->user_id = $this->user->id;
            $this->address->is_default = ! $this->user->addresses()->count() ? true : $this->address->is_default;
            $this->address->save();
            $this->address = new Address;
            $this->dispatch('alert', 'success', __('Registration successfully added'));
            $this->dispatch('render');
        } catch (OdooException $e) {
            report($e);
            $this->dispatch('alert', 'warning', $e->getMessage());

            return;
        } catch (Exception $e) {
            report($e);
            $this->dispatch('alert', 'warning', __('There was an error saving your registration, please try again later.'));

            return;
        }
    }
    public function update() {
        $this->validate();
        try {
            $this->address->update();
            $this->dispatch('alert', 'success', __('Registration successfully updated'));
            $this->dispatch('render');
        } catch (OdooException $e) {
            report($e);
            $this->dispatch('alert', 'warning', $e->getMessage());

            return;
        } catch (Exception $e) {
            report($e);
            $this->dispatch('alert', 'warning', __('There was an error saving your registration, please try again later.'));

            return;
        }
    }

    // Loads
    private function loadCountry() {
        if (isset($this->address->state->country->id)) {
            $this->countryId = $this->address->state->country->id;
        } else {
            $this->countryId = Country::query()->validate()->where('default', true)->first()->id;
        }
        $this->loadCountryCode();
    }
    private function loadCountryCode() {
        if ($this->countryId) {
            $this->countryCode = Country::query()->validate()->where('id', $this->countryId)->first()->code;
        }
    }
    private function loadCountries() {
        $this->countries = Country::query()->validate()->orderBy('id', 'desc')->get();
    }
    private function loadStates() {
        $this->states = State::query()->validate()->orderByDesc('name')->where('country_id', $this->countryId)->get();
    }
    private function loadUseCfdis() {
        $this->useCfdis = UseCfdi::orderByDesc('code')->get();
    }
    private function loadRegimes() {
        $this->fiscalRegimes = FiscalRegime::orderByDesc('code')->get();
    }

    // UPDATES MAGIC
    public function updatedCountryId() {
        $this->loadStates();
        $this->loadCountryCode();
        if ($this->countryCode != 'MX') {
            $this->address->use_cfdi_id = null;
            $this->address->fiscalRegime_id = null;
        }
    }
}
