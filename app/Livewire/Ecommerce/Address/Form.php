<?php

namespace App\Livewire\Ecommerce\Address;

use App\Exceptions\OdooException;
use App\Models\Address;
use App\Models\Country;
use App\Models\FiscalRegime;
use App\Models\State;
use App\Models\UseCfdi;
use App\Models\User;
use App\Rules\ValidRFC;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Livewire\Component;

class Form extends Component
{
    protected $listeners = ['fill-billing-address' => 'fillBillingAddress'];
    public User $user;
    public Address $address;
    public int $countryId;
    public string $countryCode;
    public Collection $countries;
    public Collection $states;
    public Collection $useCfdis;
    public Collection $fiscalRegimes;
    public bool $redirect = true;
    public bool $isBilling = false;
    public ?string $target = null;

    protected function rules() {
        return [
            'address.state_id' => 'required',
            'address.municipality' => 'required',
            'address.colony' => 'required',
            'address.zip_code' => 'required|min:5|max:6',
            'address.street' => 'required',
            'address.street_between' => 'nullable',
            'address.street_references' => 'nullable',
            'address.name' => 'required',
            'address.phone' => 'required',
            'address.email' => 'required|email',
            'address.is_billing' => 'nullable|boolean',
            'address.is_billing_default' => 'nullable|boolean',
            'address.is_default' => 'nullable|boolean',
            'address.vat' => [$this->address->is_billing ? 'required' : 'nullable', new ValidRFC],
            'address.company' => $this->address->is_billing ? 'required' : 'nullable',
            'address.use_cfdi_id' => ($this->address->is_billing && $this->countryCode == 'MX') ? 'required' : 'nullable',
            'address.fiscal_regime_id' => ($this->address->is_billing && $this->countryCode == 'MX') ? 'required' : 'nullable',
        ];
    }
    public function mount(Address $address) {
        $this->user = User::find(Auth::id());
        $this->address = $address;
        $this->address->load('state.country');
        $this->address->is_billing = $this->address->exists ? $this->address->is_billing : $this->isBilling;
        $this->address->name = $this->address->exists ? $this->address->name : $this->user->name;
        $this->address->email = $this->address->exists ? $this->address->email : $this->user->email;
        $this->address->phone = $this->address->exists ? $this->address->phone : $this->user->phone;

        $this->loadCountry();
        $this->loadCountries();
        $this->loadStates();
        $this->loadUseCfdis();
        $this->loadRegimes();
    }
    public function render() {
        return view('livewire.ecommerce.address.form');
    }
    public function save() {
        $this->normalizeBillingFlags();
        $this->validate();
        try {
            $this->address->user_id = $this->user->id;
            $this->address->is_default = ! $this->user->addresses()->count() ? true : $this->address->is_default;
            $this->address->save();
            $this->dispatch('alert', 'success', __('Registration successfully added'));

            return $this->redirect
                ? Redirect::route('ecommerce.account.address.index')
                : $this->dispatch('address-saved', id: $this->address->id, target: $this->target);
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
    private function normalizeBillingFlags() {
        if (! $this->address->is_billing) {
            $this->address->is_billing_default = false;
        }
    }

    // LOADS
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
        $this->countries = Country::query()->validate()->orderBy('id', 'asc')->validate()->get();
    }
    public function loadStates() {
        $this->states = State::query()->validate()->orderBy('name', 'asc')->validate()->where('country_id', $this->countryId)->get();
    }
    private function loadUseCfdis() {
        $this->useCfdis = UseCfdi::orderBy('code', 'asc')->get();
    }
    private function loadRegimes() {
        $this->fiscalRegimes = FiscalRegime::orderBy('code', 'asc')->get();
    }
    public function fillBillingAddress(int $id, ?string $target = null) {
        if ($target && $this->target != $target) {
            return;
        }
        $address = Address::with(['state.country'])->findOrFail($id);
        $this->countryId = $address->state->country->id ?? null;
        $this->countryCode = $address->state->country->code ?? null;
        $this->address->state_id = $address->state_id;
        $this->address->municipality = $address->municipality;
        $this->address->colony = $address->colony;
        $this->address->zip_code = $address->zip_code;
        $this->address->street = $address->street;
        $this->address->street_between = $address->street_between;
        $this->address->street_references = $address->street_references;
        $this->address->company = $address->company;
        $this->address->name = $address->name;
        $this->address->phone = $address->phone;
        $this->address->email = $address->email;
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
