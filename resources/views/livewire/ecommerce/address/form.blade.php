<div>
    <div x-data="ecommerceAddressForm($wire)">
        <form wire:submit.prevent="save">
            @include('ecommerce.components.alert')

            {{-- SECCIÓN 1: Datos personales --}}
            <h5 class="subtitle font-weight-bold ls-25 mb-3 mt-2">{{ __('General information') }}</h5>
            <div class="row gutter-sm">
                <div class="col-lg-6">
                    <div class="form-group">
                        <label>{{ __('Full name') }} <span class="required">*</span></label>
                        <input wire:model="address.name" name="name" required type="text"
                            placeholder="{{ __('Full name') }}"
                            class="form-control form-control-md @error('address.name') is-invalid @enderror" />
                        @error('address.name')
                            <small class="form-text text-danger" role="alert">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="form-group">
                        <label>{{ __('Email') }} <span class="required">*</span></label>
                        <input wire:model="address.email" name="email" required type="email"
                            placeholder="correo@ejemplo.com"
                            class="form-control form-control-md @error('address.email') is-invalid @enderror" />
                        @error('address.email')
                            <small class="form-text text-danger" role="alert">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
            </div>
            <div class="row gutter-sm">
                <div class="col-lg-6">
                    <div class="form-group">
                        <label>{{ __('Phone') }} <span class="required">*</span></label>
                        <input wire:model="address.phone" name="phone" required type="tel"
                            placeholder="33xxxxxxxx"
                            class="form-control form-control-md @error('address.phone') is-invalid @enderror" />
                        @error('address.phone')
                            <small class="form-text text-danger" role="alert">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- SECCIÓN 2: Ubicación --}}
            <hr class="mt-1 mb-4">
            <h5 class="subtitle font-weight-bold ls-25 mb-3">{{ __('Street address') }}</h5>
            <div class="row gutter-sm">
                <div class="col-lg-6">
                    <div class="form-group">
                        <label>{{ __('Country') }} <span class="required">*</span></label>
                        <div class="select-box">
                            <select wire:model="countryId" name="countryId" required
                                class="form-control form-control-md @error('countryId') is-invalid @enderror">
                                <option value="">{{ __('Select a country') }}</option>
                                @foreach ($countries as $country)
                                    <option value="{{ $country->id }}">{{ $country->name }}</option>
                                @endforeach
                            </select>
                            @error('countryId')
                                <small class="form-text text-danger" role="alert">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="form-group">
                        <label>{{ __('Zip code') }} <span class="required">*</span></label>
                        <input wire:model="address.zip_code" name="zip_code" required type="number"
                            placeholder="Ejem: 44100"
                            class="form-control form-control-md @error('address.zip_code') is-invalid @enderror" />
                        @error('address.zip_code')
                            <small class="form-text text-danger" role="alert">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
            </div>
            <div class="row gutter-sm">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>{{ __('State') }} <span class="required">*</span></label>
                        <div class="select-box">
                            <select wire:model="address.state_id" name="state_id" required
                                class="form-control form-control-md @error('address.state_id') is-invalid @enderror">
                                <option value="">{{ __('Select a state') }}</option>
                                @foreach ($states as $state)
                                    <option value="{{ $state->id }}">{{ $state->name }}</option>
                                @endforeach
                            </select>
                            @error('address.state_id')
                                <small class="form-text text-danger" role="alert">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="form-group">
                        <label>{{ __('Municipality') }} <span class="required">*</span></label>
                        <input wire:model="address.municipality" name="municipality" required type="text"
                            placeholder="{{ __('Municipality') }}"
                            class="form-control form-control-md @error('address.municipality') is-invalid @enderror" />
                        @error('address.municipality')
                            <small class="form-text text-danger" role="alert">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label>{{ __('Street address and number') }} <span class="required">*</span></label>
                <input wire:model="address.street" required name="street" type="text"
                    placeholder="{{ __('Street address and number') }}"
                    class="form-control form-control-md @error('address.street') is-invalid @enderror">
                @error('address.street')
                    <small class="form-text text-danger" role="alert">{{ $message }}</small>
                @enderror
            </div>
            <div class="row gutter-sm">
                <div class="col-md-4">
                    <div class="form-group">
                        <label>{{ __('Colony') }} <span class="required">*</span></label>
                        <input wire:model="address.colony" name="colony" required type="text"
                            placeholder="Ejem: {{ __('Colony') }}"
                            class="form-control form-control-md @error('address.colony') is-invalid @enderror" />
                        @error('address.colony')
                            <small class="form-text text-danger" role="alert">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label>{{ __('Between streets') }} <span class="text-muted">({{ __('optional') }})</span></label>
                <input wire:model="address.street_between" type="text"
                    placeholder="{{ __('Between streets') }}"
                    class="form-control form-control-md @error('address.street_between') is-invalid @enderror">
                @error('address.street_between')
                    <small class="form-text text-danger" role="alert">{{ $message }}</small>
                @enderror
            </div>
            <div class="form-group">
                <label>{{ __('Street references') }} <span class="text-muted">({{ __('optional') }})</span></label>
                <textarea wire:model="address.street_references" name="street_references"
                    class="form-control mb-0 @error('address.street_references') is-invalid @enderror"
                    cols="30" rows="3"
                    placeholder="{{ __('Notes about your order, e.g special notes for delivery') }}"></textarea>
            </div>

            {{-- SECCIÓN 3: Opciones de dirección --}}
            <hr class="mt-1 mb-4">
            <h5 class="subtitle font-weight-bold ls-25 mb-3">{{ __('Address options') }}</h5>
            <div class="row mt-2">
                <div class="col-md-4">
                    <div class="form-group checkbox-toggle pb-2">
                        <input wire:model="address.is_default"
                            class="custom-checkbox @error('address.is_default') is-invalid @enderror"
                            type="checkbox"
                            id="ecommerce_address_default_{{ $address->id ?? 'new' }}_{{ $this->getId() }}" />
                        <label for="ecommerce_address_default_{{ $address->id ?? 'new' }}_{{ $this->getId() }}">{{ __('Set as default') }}</label>
                    </div>
                    @error('address.is_default')
                        <small class="form-text text-danger" role="alert">{{ $message }}</small>
                    @enderror
                </div>
                <div class="col-md-4">
                    <div class="form-group checkbox-toggle pb-2">
                        <input wire:model="address.is_billing"
                            x-model="isBilling"
                            x-on:change="toggleIsBilling()"
                            class="custom-checkbox @error('address.is_billing') is-invalid @enderror"
                            type="checkbox"
                            id="ecommerce_address_billing_{{ $address->id ?? 'new' }}_{{ $this->getId() }}" />
                        <label for="ecommerce_address_billing_{{ $address->id ?? 'new' }}_{{ $this->getId() }}">{{ __('Billing address') }}</label>
                    </div>
                    @error('address.is_billing')
                        <small class="form-text text-danger" role="alert">{{ $message }}</small>
                    @enderror
                </div>
                <div class="col-md-4" x-show="isBilling" x-cloak>
                    <div class="form-group checkbox-toggle pb-2">
                        <input wire:model="address.is_billing_default"
                            x-model="isBillingDefault"
                            x-bind:disabled="!isBilling"
                            class="custom-checkbox @error('address.is_billing_default') is-invalid @enderror"
                            type="checkbox"
                            id="ecommerce_address_billing_default_{{ $address->id ?? 'new' }}_{{ $this->getId() }}" />
                        <label for="ecommerce_address_billing_default_{{ $address->id ?? 'new' }}_{{ $this->getId() }}">{{ __('Default billing') }}</label>
                    </div>
                    @error('address.is_billing_default')
                        <small class="form-text text-danger" role="alert">{{ $message }}</small>
                    @enderror
                </div>
            </div>

            {{-- SECCIÓN 4: Información de facturación (visible solo si is_billing) --}}
            <div x-show="isBilling" x-cloak>
                <hr class="mt-1 mb-4">
                <h5 class="subtitle font-weight-bold ls-25 mb-3">{{ __('Billing information') }}</h5>
                <div class="row gutter-sm">
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label>{{ __('VAT') }} <span class="required">*</span></label>
                            <input wire:model="address.vat" minlength="12" maxlength="13" name="vat" type="text"
                                placeholder="Ejem: XAXX010101000"
                                class="form-control form-control-md @error('address.vat') is-invalid @enderror">
                            @error('address.vat')
                                <small class="form-text text-danger" role="alert">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label>{{ __('Company name') }} <span class="required">*</span></label>
                            <input wire:model="address.company" name="company" type="text"
                                placeholder="Ejem: {{ __('Company name') }}"
                                class="form-control form-control-md @error('address.company') is-invalid @enderror">
                            @error('address.company')
                                <small class="form-text text-danger" role="alert">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                </div>
                @if($countryCode == 'MX')
                    <div class="row gutter-sm">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>{{ __('Use of cfdi') }} <span class="required">*</span></label>
                                <div class="select-box">
                                    <select wire:model="address.use_cfdi_id" name="address.use_cfdi_id"
                                        class="form-control form-control-md @error('address.use_cfdi_id') is-invalid @enderror">
                                        <option value="">{{ __('Select a option') }}</option>
                                        @foreach ($useCfdis as $useCfdi)
                                            <option value="{{ $useCfdi->id }}">{{ $useCfdi->code }} - {{ $useCfdi->description }}</option>
                                        @endforeach
                                    </select>
                                    @error('address.use_cfdi_id')
                                        <small class="form-text text-danger" role="alert">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>{{ __('Fiscal regime') }} <span class="required">*</span></label>
                                <div class="select-box">
                                    <select wire:model="address.fiscal_regime_id" name="address.fiscal_regime_id"
                                        class="form-control form-control-md @error('address.fiscal_regime_id') is-invalid @enderror">
                                        <option value="">{{ __('Select a option') }}</option>
                                        @foreach ($fiscalRegimes as $fiscalRegime)
                                            <option value="{{ $fiscalRegime->id }}">{{ $fiscalRegime->code }} - {{ $fiscalRegime->description }}</option>
                                        @endforeach
                                    </select>
                                    @error('address.fiscal_regime_id')
                                        <small class="form-text text-danger" role="alert">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <div class="d-grid pt-4">
                <button wire:target.prevent="save" wire:loading.class="load-more-overlay loading" wire:loading.attr="disabled" type="submit" class="btn btn-dark btn-rounded">
                    <span wire:loading wire:target="save" class="spinner-border spinner-border-sm align-middle me-2"></span>
                    {{ __('Confirm address') }}
                </button>
            </div>
        </form>
    </div>
</div>

@script
    <script>
        Alpine.data('ecommerceAddressForm', (wire) => ({
            isBilling: wire.entangle('address.is_billing'),
            isBillingDefault: wire.entangle('address.is_billing_default'),

            init(){
            },
            toggleIsBilling() {
                if(!this.isBilling){
                    this.isBillingDefault = false;
                }
            },
        }));
    </script>
@endscript
