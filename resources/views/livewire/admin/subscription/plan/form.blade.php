<div>
    <form class="form" wire:submit.prevent="save">
        <div class="fv-row mb-7">
            <label class="fs-6 fw-bold form-label mb-2">
                <span class="required">{{ __('Titulo') }}</span>
            </label>
            <input wire:model="plan.title" class="form-control form-control-solid @error("plan.title") is-invalid @enderror"/>
            @error("plan.title") <small class="form-text text-danger" role="alert">{{ $message }}</small> @enderror
        </div>
        <div class="fv-row mb-7">
            <label class="fs-6 fw-bold form-label mb-2">
                <span class="">{{ __('Subtitulo') }}</span>
            </label>
            <input wire:model="plan.subtitle" class="form-control form-control-solid @error("plan.subtitle") is-invalid @enderror"/>
            @error("plan.subtitle") <small class="form-text text-danger" role="alert">{{ $message }}</small> @enderror
        </div>
        <div class='separator separator-dashed my-5'></div>
        <div class="fv-row mb-7">
            <label class="fs-6 fw-bold form-label mb-2">
                <span class="">{{ __('Stripe Id') }}</span>
                <i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="popover" data-bs-trigger="hover" data-bs-html="true" data-bs-content="{{ __('Id del producto de stripe') }}"></i>
            </label>
            <input wire:model="plan.stripe_id" class="form-control form-control-solid @error('plan.stripe_id') is-invalid @enderror"/>
            @error('plan.stripe_id') <small class="form-text text-danger" role="alert">{{ $message }}</small> @enderror
        </div>
        <div class="fv-row mb-7">
            <label class="fs-6 fw-bold form-label mb-2">
                <span class="">{{ __('Stripe producto nombre') }}</span>
                <i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="popover" data-bs-trigger="hover" data-bs-html="true" data-bs-content="{{ __('Nombre del producto de stripe') }}"></i>
            </label>
            <input wire:model="plan.stripe_product_name" class="form-control form-control-solid @error('plan.stripe_product_name') is-invalid @enderror"/>
            @error('plan.stripe_product_name') <small class="form-text text-danger" role="alert">{{ $message }}</small> @enderror
        </div>
        <div class="fv-row mb-7">
            <label class="fs-6 fw-bold form-label mb-2">
                <span class="">{{ __('Stripe precio por mes') }}</span>
                <i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="popover" data-bs-trigger="hover" data-bs-html="true" data-bs-content="{{ __('Id del precio del producto de stripe por mes') }}"></i>
            </label>
            <input wire:model="plan.stripe_price_month_id" class="form-control form-control-solid @error('plan.stripe_price_month_id') is-invalid @enderror"/>
            @error('plan.stripe_price_month_id') <small class="form-text text-danger" role="alert">{{ $message }}</small> @enderror
        </div>
        <div class="fv-row mb-7">
            <label class="fs-6 fw-bold form-label mb-2">
                <span class="">{{ __('Stripe precio por año') }}</span>
                <i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="popover" data-bs-trigger="hover" data-bs-html="true" data-bs-content="{{ __('Id del precio del producto de stripe por año') }}"></i>
            </label>
            <input wire:model="plan.stripe_price_year_id" class="form-control form-control-solid @error('plan.stripe_price_year_id') is-invalid @enderror"/>
            @error('plan.stripe_price_year_id') <small class="form-text text-danger" role="alert">{{ $message }}</small> @enderror
        </div>
        <div class="fv-row mb-7">
            <label class="fs-6 fw-bold form-label mb-2">
                <span class="required">{{ __('Monto por mes') }}</span>
            </label>
            <input wire:model="plan.amount_month" type="number" required class="form-control form-control-solid @error('plan.amount_month') is-invalid @enderror"/>
            @error('plan.amount_month') <small class="form-text text-danger" role="alert">{{ $message }}</small> @enderror
        </div>
        <div class="fv-row mb-7">
            <label class="fs-6 fw-bold form-label mb-2">
                <span class="required">{{ __('Monto por año') }}</span>
            </label>
            <input wire:model="plan.amount_year" type="number" required class="form-control form-control-solid @error('plan.amount_year') is-invalid @enderror"/>
            @error('plan.amount_year') <small class="form-text text-danger" role="alert">{{ $message }}</small> @enderror
        </div>
        <div class="fv-row mb-7">
            <label class="fs-6 fw-bold form-label mb-2">
                <span class="">{{ __('Cantidad de días gratis') }}</span>
            </label>
            <input wire:model="plan.free_trial_days" type="number" class="form-control form-control-solid @error('plan.free_trial_days') is-invalid @enderror"/>
            @error('plan.free_trial_days') <small class="form-text text-danger" role="alert">{{ $message }}</small> @enderror
        </div>
        <div class='separator separator-dashed my-5'></div>
        <div class="fv-row mb-7">
            <label class="fs-6 fw-bold form-label mb-2">
                <span class="required">{{ __('Estado') }}</span>
            </label>
            <select wire:model="plan.status" required class="form-control form-control-solid @error('plan.status') is-invalid @enderror">
                <option value="">{{ __('Selecciona una opción') }}</option>
                <option value="1">{{ __('Activo') }}</option>
                <option value="0">{{ __('Inactivo') }}</option>
            </select>
            @error('plan.status') <small class="form-text text-danger" role="alert">{{ $message }}</small> @enderror
        </div>
        <div class="fv-row mb-7">
            <label class="fs-6 fw-bold form-label mb-2">
                <span class="required">{{ __('¿Destacado?') }}</span>
            </label>
            <select wire:model="plan.featured" required class="form-control form-control-solid @error('plan.featured') is-invalid @enderror">
                <option value="">{{ __('Selecciona una opción') }}</option>
                <option value="1">{{ __('Si') }}</option>
                <option value="0">{{ __('No') }}</option>
            </select>
            @error('plan.featured') <small class="form-text text-danger" role="alert">{{ $message }}</small> @enderror
        </div>
        <div class="fv-row mb-7">
            <label class="fs-6 fw-bold form-label mb-2">
                <span class="required">{{ __('Ordenamiento') }}</span>
            </label>
            <input wire:model="plan.order" type="number" class="form-control form-control-solid @error("plan.order") is-invalid @enderror"/>
            @error("plan.order") <small class="form-text text-danger" role="alert">{{ $message }}</small> @enderror
        </div>
        <div class='separator separator-dashed my-5'></div>
        <div class="fv-row mb-7">
            <label class="fs-6 fw-bold form-label mb-2">
                <span class="required">{{ __('Caracteristicas') }}</span>
                <i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="popover" data-bs-trigger="hover" data-bs-html="true" data-bs-content="{{ __('Para seleccionar multiples opciones, manten precionado la tecla shif') }}"></i>
            </label>
            <select wire:model="planFeaturesArray" multiple class="form-control form-control-solid @error('planFeaturesArray') is-invalid @enderror">
                <option value="">{{ __('Selecciona las caracteristicas') }}</option>
                @foreach($planFeatures as $planFeature)
                    <option value="{{ $planFeature->id }}">{{ $planFeature->name }}</option>
                @endforeach
            </select>
            @error('planFeaturesArray') <small class="form-text text-danger" role="alert">{{ $message }}</small> @enderror
        </div>
        <div class='separator separator-dashed my-5'></div>
        <div class="fv-row mb-7">
            <label class="fs-5 fw-bolder form-label mb-2">{{ __('Permisos') }}</label>
            <div class="table-responsive">
                <table class="table align-middle table-row-dashed fs-6 gy-5">
                    <tbody>
                        @foreach($permissions as $group => $items)
                            <tr>
                                <td class="text-gray-900 fw-bold">{{ ucfirst($group) }}</td>
                                <td></td>
                            </tr>
                            @foreach($items as $permission)
                                <tr>
                                    <td class="text-gray-800 ps-4">
                                        {{ $permission->alias }}
                                    </td>
                                    <td>
                                        <div class="d-flex">
                                            <label class="form-check form-check-sm form-check-custom form-check-solid me-5 me-lg-20">
                                                <input wire:model="permissionsArray" class="form-check-input" type="checkbox" value="{{ $permission->name }}" />
                                                <span class="form-check-label">{{ __('Select') }}</span>
                                            </label>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="text-end pt-15">
            <button type="reset" class="btn btn-light me-3" data-bs-dismiss="modal"><i class="fa fa-arrow-left"></i></button>
            <button wire:loading.attr="disabled" wire:target="save" type="submit" class="btn btn-primary">
                <span class="indicator-label">{{ __('Salvar cambios') }}</span>
                <span wire:loading wire:target="save" class="spinner-border spinner-border-sm align-middle ms-2"></span>
            </button>
        </div>
    </form>
</div>
