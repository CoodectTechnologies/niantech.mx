<div>
    @include('admin.components.errors')
    <div x-data="form($wire)">
        <form class="form" wire:submit.prevent="{{ $method }}">
            <div wire:ignore.self class="d-flex flex-column scroll-y me-n7 pe-7">
                
                {{-- SECCIÓN 1: Datos personales --}}
                <div class="row">
                    <div class="col-lg-6">
                        <div class="fv-row mb-7">
                            <label class="fs-6 fw-bolder form-label mb-2">
                                <span class="required">{{ __('Full name') }}</span>
                            </label>
                            <input required wire:model="address.name"
                                class="form-control form-control-solid @error('address.name') invalid-feedback @enderror"
                                placeholder="{{ __('Full name') }}" />
                            @error('address.name')
                                <small class="form-text text-danger" role="alert">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="fv-row mb-7">
                            <label class="fs-6 fw-bolder form-label mb-2">
                                <span class="required">{{ __('Email') }}</span>
                            </label>
                            <input required type="email" wire:model="address.email"
                                class="form-control form-control-solid @error('address.email') invalid-feedback @enderror"
                                placeholder="Correo electronico del cliente" />
                            @error('address.email')
                                <small class="form-text text-danger" role="alert">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6">
                        <div class="fv-row mb-7">
                            <label class="fs-6 fw-bolder form-label mb-2">
                                <span class="required">{{ __('Phone') }}</span>
                            </label>
                            <input type="tel" required wire:model="address.phone"
                                class="form-control form-control-solid @error('address.phone') invalid-feedback @enderror"
                                placeholder="Ejem: 33xxxxxxxx" />
                            @error('address.phone')
                                <small class="form-text text-danger" role="alert">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="fv-row mb-7">
                            <label class="fs-6 fw-bolder form-label mb-2">
                                <span class="required">{{ __('Zip code') }}</span>
                            </label>
                            <input required wire:model="address.zip_code"
                                type="number"
                                class="form-control form-control-solid @error('address.zip_code') invalid-feedback @enderror"
                                placeholder="" />
                            @error('address.zip_code')
                                <small class="form-text text-danger" role="alert">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- SECCIÓN 2: Ubicación --}}
                <div class="fv-row mb-7">
                    <label class="fs-6 fw-bolder form-label mb-2">
                        <span class="required">{{ __('Country') }}</span>
                    </label>
                    <select required wire:model="countryId"
                        class="form-select form-select-solid @error('countryId') invalid-feedback @enderror">
                        <option value="">{{ __('Select a option') }}</option>
                        @foreach($countries as $c)
                            <option value="{{ $c->id }}">{{ $c->name }}</option>
                        @endforeach
                    </select>
                    @error('countryId')
                        <small class="form-text text-danger" role="alert">{{ $message }}</small>
                    @enderror
                </div>
                <div class="fv-row mb-7">
                    <label class="fs-6 fw-bolder form-label mb-2">
                        <span class="required">{{ __('State') }}</span>
                    </label>
                    <select wire:model="address.state_id"
                        class="form-select form-select-solid @error('address.state_id') invalid-feedback @enderror">
                        <option value="">{{ __('Select a option') }}</option>
                        @foreach($states as $state)
                            <option value="{{ $state->id }}">{{ $state->name }}</option>
                        @endforeach
                    </select>
                    @error('address.state_id')
                        <small class="form-text text-danger" role="alert">{{ $message }}</small>
                    @enderror
                </div>
                <div class="fv-row mb-7">
                    <label class="fs-6 fw-bolder form-label mb-2">
                        <span class="required">{{ __('Municipality') }}</span>
                    </label>
                    <input required wire:model="address.municipality"
                        class="form-control form-control-solid @error('address.municipality') invalid-feedback @enderror"
                        placeholder="Ejem: Guadalajara" />
                    @error('address.municipality')
                        <small class="form-text text-danger" role="alert">{{ $message }}</small>
                    @enderror
                </div>
                <div class="fv-row mb-7">
                    <label class="fs-6 fw-bolder form-label mb-2">
                        <span class="required">{{ __('Colony') }}</span>
                    </label>
                    <input required wire:model="address.colony" class="form-control form-control-solid @error('address.colony') invalid-feedback @enderror" />
                    @error('address.colony')
                        <small class="form-text text-danger" role="alert">{{ $message }}</small>
                    @enderror
                </div>
                <div class="fv-row mb-7">
                    <label class="fs-6 fw-bolder form-label mb-2">
                        <span class="required">{{ __('Street address and number') }}</span>
                    </label>
                    <input required wire:model="address.street"
                        class="form-control form-control-solid @error('address.street') invalid-feedback @enderror"
                        placeholder="" />
                    @error('address.street')
                        <small class="form-text text-danger" role="alert">{{ $message }}</small>
                    @enderror
                </div>

                {{-- NUEVO: Campo Entre calles (Agregado del segundo código) --}}
                <div class="fv-row mb-7">
                    <label class="fs-6 fw-bolder form-label mb-2">
                        <span class="">{{ __('Between streets') }}</span>
                    </label>
                    <input type="text" wire:model="address.street_between"
                        class="form-control form-control-solid @error('address.street_between') invalid-feedback @enderror"
                        placeholder="{{ __('Between streets') }}" />
                    @error('address.street_between')
                        <small class="form-text text-danger" role="alert">{{ $message }}</small>
                    @enderror
                </div>

                <div class="fv-row mb-7">
                    <label class="fs-6 fw-bolder form-label mb-2">
                        <span class="">{{ __('Street references') }}</span>
                    </label>
                    <textarea wire:model="address.street_references" cols="30" rows="3"
                        class="form-control form-control-solid @error('address.street_references') invalid-feedback @enderror"
                        placeholder="">{{ $address->street_references }}</textarea>
                    @error('address.street_references')
                        <small class="form-text text-danger" role="alert">{{ $message }}</small>
                    @enderror
                </div>

                {{-- SECCIÓN 3: Opciones de dirección --}}
                <div class="row">
                    <div class="col">
                        <div class="form-check form-check-custom form-check-solid mb-7">
                            <input wire:model="address.is_default"
                                class="form-check-input me-3 @error('address.is_default') invalid-feedback @enderror"
                                type="checkbox"
                                id="kt_shipping_address_default_option_{{ $user->id }}_{{ $address->id ?? 'new' }}" />
                            <label class="form-check-label" for="kt_shipping_address_default_option_{{ $user->id }}_{{ $address->id ?? 'new' }}">
                                <div class="fw-bolder text-gray-800">{{ __('Set as default') }}</div>
                            </label>
                        </div>
                        @error('address.is_default')
                            <small class="form-text text-danger" role="alert">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="col">
                        <div class="form-check form-check-custom form-check-solid mb-7">
                            <input wire:model="address.is_billing"
                                x-model="isBilling"
                                x-on:change="toggleIsBilling()"
                                class="form-check-input me-3 @error('address.is_billing') invalid-feedback @enderror"
                                type="checkbox"
                                id="kt_shipping_address_billing_option_{{ $user->id }}_{{ $address->id ?? 'new' }}" />
                            <label class="form-check-label" for="kt_shipping_address_billing_option_{{ $user->id }}_{{ $address->id ?? 'new' }}">
                                <div class="fw-bolder text-gray-800">{{ __('Billing address') }}</div>
                            </label>
                        </div>
                        @error('address.is_billing')
                            <small class="form-text text-danger" role="alert">{{ $message }}</small>
                        @enderror
                    </div>
                    
                    {{-- Condicionado por Alpine: Solo se muestra si isBilling es true --}}
                    <div class="col" x-show="isBilling" x-cloak>
                        <div class="form-check form-check-custom form-check-solid mb-7">
                            <input wire:model="address.is_billing_default"
                                x-model="isBillingDefault"
                                x-bind:disabled="!isBilling"
                                class="form-check-input me-3 @error('address.is_billing_default') invalid-feedback @enderror"
                                type="checkbox"
                                id="kt_shipping_address_billing_default_option_{{ $user->id }}_{{ $address->id ?? 'new' }}" />
                            <label class="form-check-label" for="kt_shipping_address_billing_default_option_{{ $user->id }}_{{ $address->id ?? 'new' }}">
                                <div class="fw-bolder text-gray-800">{{ __('Default billing') }}</div>
                            </label>
                        </div>
                        @error('address.is_billing_default')
                            <small class="form-text text-danger" role="alert">{{ $message }}</small>
                        @enderror
                    </div>
                </div>

                {{-- SECCIÓN 4: Información de facturación (Oculta dinámicamente si no se requiere factura) --}}
                <div x-show="isBilling" x-cloak>
                    <div class="fv-row mb-7">
                        <label class="fs-6 fw-bolder form-label mb-2">
                            <span class="">{{ __('VAT') }}</span>
                        </label>
                        <input wire:model="address.vat"
                            class="form-control form-control-solid @error('address.vat') invalid-feedback @enderror"
                            placeholder="XAXX010101000" />
                        @error('address.vat')
                            <small class="form-text text-danger" role="alert">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="fv-row mb-7">
                        <label class="fs-6 fw-bolder form-label mb-2">
                            <span class="">{{ __('Company name') }}</span>
                        </label>
                        <input wire:model="address.company"
                            class="form-control form-control-solid @error('address.company') invalid-feedback @enderror"
                            placeholder="" />
                        @error('address.company')
                            <small class="form-text text-danger" role="alert">{{ $message }}</small>
                        @enderror
                    </div>

                    @if($countryCode == 'MX')
                        <div class="fv-row mb-7">
                            <label class="fs-6 fw-bolder form-label mb-2">
                                <span class="">{{ __('Use of cfdi') }}</span>
                            </label>
                            <select wire:model="address.use_cfdi_id"
                                class="form-select form-select-solid @error('address.use_cfdi_id') invalid-feedback @enderror">
                                <option value="">{{ __('Select a option') }}</option>
                                @foreach($useCfdis as $useCfdi)
                                    <option value="{{ $useCfdi->id }}">{{ $useCfdi->code }} - {{ $useCfdi->description }}</option>
                                @endforeach
                            </select>
                            @error('address.use_cfdi_id')
                                <small class="form-text text-danger" role="alert">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="fv-row mb-7">
                            <label class="fs-6 fw-bolder form-label mb-2">
                                <span class="">{{ __('Fiscal regime') }}</span>
                            </label>
                            <select wire:model="address.fiscal_regime_id"
                                class="form-select form-select-solid @error('address.fiscal_regime_id') invalid-feedback @enderror">
                                <option value="">{{ __('Select a option') }}</option>
                                @foreach($fiscalRegimes as $fiscalRegime)
                                    <option value="{{ $fiscalRegime->id }}">{{ $fiscalRegime->code }} - {{ $fiscalRegime->description }}</option>
                                @endforeach
                            </select>
                            @error('address.fiscal_regime_id')
                                <small class="form-text text-danger" role="alert">{{ $message }}</small>
                            @enderror
                        </div>
                    @endif
                </div>

                @if(config('services.odoo.status'))
                    <div class="fv-row mb-7">
                        <label class="fs-6 fw-bolder form-label mb-2">
                            <span class="">Proveedor</span>
                        </label>
                        <input wire:model="address.provider"
                            class="form-control form-control-solid @error('address.provider') invalid-feedback @enderror" />
                        @error('address.provider')
                            <small class="form-text text-danger" role="alert">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="fv-row mb-7">
                        <label class="fs-6 fw-bolder form-label mb-2">
                            <span class="">Proveedor id</span>
                        </label>
                        <input wire:model="address.provider_id"
                            class="form-control form-control-solid @error('address.provider_id') invalid-feedback @enderror" />
                        @error('address.provider_id')
                            <small class="form-text text-danger" role="alert">{{ $message }}</small>
                        @enderror
                    </div>
                @endif
            </div>
            
            <div class="text-center pt-15">
                <button type="reset" class="btn btn-light me-3" data-bs-dismiss="modal"><i
                        class="fa fa-arrow-left"></i></button>
                <button wire:loading.attr="disabled" wire:target="{{ $method }}" type="submit"
                    class="btn btn-primary">
                    <span class="indicator-label">{{ __('Save changes') }}</span>
                    <span wire:loading wire:target="{{ $method }}"
                        class="spinner-border spinner-border-sm align-middle ms-2"></span>
                </button>
            </div>
            </form>
    </div>
</div>

@script
    <script>
        Alpine.data('form', (wire) => ({
            isBilling: wire.entangle('address.is_billing'),
            isBillingDefault: wire.entangle('address.is_billing_default'),

            init(){
                
            },
            toggleIsBilling() {
                if (!this.isBilling) {
                    this.isBillingDefault = false;
                }
            },
        }));
    </script>
@endscript