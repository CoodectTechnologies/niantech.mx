<div>
    <div>
        @include('admin.components.errors')
        <!--begin::Form-->
        <form wire:submit.prevent="{{ $method }}" class="form d-flex flex-column flex-lg-row">
            <!--begin::Thumbnail settings-->
            <div class="w-100 flex-lg-row-auto w-lg-300px mb-7 me-7 me-lg-10">
                <div class="card card-flush py-4">
                    <div class="card-body text-left pt-0">
                        <!--begin::Input group-->
                        <div class="fv-row mb-7">
                            <label class="fs-6 fw-bold form-label mb-2">
                                <span class="required">{{ __('Code') }}</span>
                            </label>
                            <input type="text" required wire:model="coupon.code"
                                class="form-control form-control-solid @error('coupon.code') invalid-feedback @enderror"
                                placeholder="Ejem: WELCOME{{ date('Y') }}" name="" />
                            @error('coupon.code')
                                <small class="form-text text-danger" role="alert">{{ $message }}</small>
                            @enderror
                        </div>
                        <!--end::Input group-->
                        <!--begin::Input group-->
                        <div class="fv-row mb-7">
                            <label class="fs-6 fw-bold form-label mb-2">
                                <span class="required">{{ __('Type coupon') }}</span>
                            </label>
                            <select wire:model.live="coupon.type_coupon" required
                                class="form-control form-control-solid @error('coupon.type_coupon') invalid-feedback @enderror">
                                <option value="">{{ __('Select a option') }}</option>
                                <option value="Fijo">{{ __('Amount fixed') }}</option>
                                <option value="Porcentaje">{{ __('Percentage') }}</option>
                            </select>
                            @error('coupon.type_coupon')
                                <small class="form-text text-danger" role="alert">{{ $message }}</small>
                            @enderror
                        </div>
                        <!--end::Input group-->
                        <!--begin::Input group-->
                        <div class="fv-row mb-7">
                            <label class="fs-6 fw-bold form-label mb-2">
                                <span class="required">{{ __('Date end') }}</span>
                            </label>
                            <input type="date" required wire:model="coupon.date_end"
                                class="form-control form-control-solid @error('coupon.date_end') invalid-feedback @enderror"
                                name="" />
                            @error('coupon.date_end')
                                <small class="form-text text-danger" role="alert">{{ $message }}</small>
                            @enderror
                        </div>
                        <!--end::Input group-->
                        <!--begin::Input group-->
                        <div class="fv-row mb-7">
                            <label class="fs-6 fw-bold form-label mb-2">
                                <span class="">{{ __('Minimum spend') }} ({{ __('Optional') }})</span>
                            </label>
                            <input type="number" wire:model="coupon.minimum_expense"
                                class="form-control form-control-solid @error('coupon.minimum_expense') invalid-feedback @enderror"
                                placeholder="" name="" />
                            @error('coupon.minimum_expense')
                                <small class="form-text text-danger" role="alert">{{ $message }}</small>
                            @enderror
                        </div>
                        <!--end::Input group-->
                        <!--begin::Input group-->
                        <div class="fv-row mb-7">
                            <!--begin::Label-->
                            <label class="form-label required">{{ __('Exclude promotional products') }}</label>
                            <!--end::Label-->
                            <select required wire:model="coupon.exclude_promotion"
                                class="form-select mb-2 @error('coupon.exclude_promotion') invalid-feedback @enderror">
                                <option value="">{{ __('Select a option') }}</option>
                                <option value="1">{{ __('Yes') }}</option>
                                <option value="0">No</option>
                            </select>
                            @error('coupon.exclude_promotion')
                                <small class="form-text text-danger" role="alert">{{ $message }}</small>
                            @enderror
                        </div>
                        <!--end::Input group-->
                        <!--begin::Input group-->
                        <div class="fv-row mb-7">
                            <label class="fs-6 fw-bold form-label mb-2">
                                <span class="">{{ __('Limit of uses') }} ({{ __('Optional') }})</span>
                            </label>
                            <input type="number" wire:model="coupon.limit_of_use"
                                class="form-control form-control-solid @error('coupon.limit_of_use') invalid-feedback @enderror"
                                placeholder="" name="" />
                            @error('coupon.limit_of_use')
                                <small class="form-text text-danger" role="alert">{{ $message }}</small>
                            @enderror
                        </div>
                        <!--end::Input group-->
                        <!--begin::Input group-->
                        <div class="fv-row mb-7 {{ $coupon->type_coupon == 'Fijo' ? 'd-block' : 'd-none' }}">
                            <label class="fs-6 fw-bold form-label mb-2">
                                <span class="required">{{ __('Amount fixed') }}</span>
                            </label>
                            <input type="number" wire:model="coupon.fixed"
                                class="form-control form-control-solid @error('coupon.fixed') invalid-feedback @enderror"
                                placeholder="" name="" />
                            @error('coupon.fixed')
                                <small class="form-text text-danger" role="alert">{{ $message }}</small>
                            @enderror
                        </div>
                        <!--end::Input group-->
                        <!--begin::Input group-->
                        <div class="fv-row mb-7 {{ $coupon->type_coupon == 'Porcentaje' ? 'd-block' : 'd-none' }}">
                            <label class="fs-6 fw-bold form-label mb-2">
                                <span class="required">{{ __('Percentage') }}</span>
                            </label>
                            <input type="number" wire:model="coupon.percentage"
                                class="form-control form-control-solid @error('coupon.percentage') invalid-feedback @enderror"
                                placeholder="" name="" />
                            @error('coupon.percentage')
                                <small class="form-text text-danger" role="alert">{{ $message }}</small>
                            @enderror
                        </div>
                        <!--end::Input group-->
                        <!--begin::Input group-->
                        <div class="fv-row mb-7">
                            <!--begin::Label-->
                            <label class="form-label required">{{ __('Active') }}</label>
                            <!--end::Label-->
                            <select required wire:model="coupon.active"
                                class="form-select mb-2 @error('coupon.active') invalid-feedback @enderror">
                                <option value="">{{ __('Select a option') }}</option>
                                <option value="1">{{ __('Yes') }}</option>
                                <option value="0">No</option>
                            </select>
                            @error('coupon.active')
                                <small class="form-text text-danger" role="alert">{{ $message }}</small>
                            @enderror
                        </div>
                        <!--end::Input group-->
                        <!--begin::Input group-->
                        <div class="fv-row">
                            <!--begin::Label-->
                            <label class="form-label required">{{ __('Currencies impacted by this coupon') }}</label>
                            <!--end::Label-->
                            @foreach($currencies as $currency)
                                <div class="form-check form-check-custom form-check-success form-check-solid mb-2">
                                    <input wire:model='currenciesArray' id="currency-{{ $currency->id }}"
                                        class="form-check-input @error('currenciesArray') invalid-feedback @enderror"
                                        type="checkbox" name="currenciesArray[]" value="{{ $currency->id }}" />
                                    <label class="form-check-label" for="currency-{{ $currency->id }}">
                                        {{ $currency->code }} ({{ $currency->name }})
                                    </label>
                                </div>
                            @endforeach
                            @error('currenciesArray')
                                <small class="form-text text-danger" role="alert">{{ $message }}</small>
                            @enderror
                        </div>
                        <!--end::Input group-->
                    </div>
                </div>
            </div>
            <div class="d-flex flex-column flex-lg-row-fluid gap-7 gap-lg-10">
                <!--begin::Order details-->
                <div class="card card-flush py-4">
                    <!--begin::Card header-->
                    <div class="card-header">
                        <div class="card-title">
                            <h2>{{ __('General data') }}</h2>
                        </div>
                    </div>
                    <!--end::Card header-->
                    <!--begin::Card body-->
                    <div class="card-body pt-0">
                        <div class="d-flex flex-column gap-10">
                            <!--begin::Input group-->
                            <div>
                                <div class="row">
                                    <!--begin::Input group-->
                                    <div class="col-lg-6">
                                        <div class="fv-row mb-5">
                                            <!--begin::Label-->
                                            <label class="required form-label">{{ __('Type') }}</label>
                                            <!--end::Label-->
                                            <!--begin::Input-->
                                            <select wire:model="coupon.type" wire:change="changeCouponType()" required
                                                class="form-select mb-2">
                                                <option value="">{{ __('Select a option') }}</option>
                                                <option value="Todos">{{ __('All the products') }}</option>
                                                <option value="Categoría">{{ __('By category') }}</option>
                                                <option value="Marca">{{ __('By brand') }}</option>
                                                <option value="Producto">{{ __('By product') }}</option>
                                            </select>
                                            <!--end::Input-->
                                            @error('coupon.type')
                                                <small class="form-text text-danger"
                                                    role="alert">{{ $message }}</small>
                                            @enderror
                                        </div>
                                        <!--end::Input group-->
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="fv-row mb-5">
                                            <!--begin::Label-->
                                            <label class="required form-label">{{ __('Conditional') }}</label>
                                            <!--end::Label-->
                                            <!--begin::Input-->
                                            <select wire:model="coupon.conditional"
                                                @if($coupon->type && $coupon->type != 'Todos') required @endif
                                                class="form-select mb-2">
                                                @if($coupon->type && $coupon->type != 'Todos')
                                                    <option value="">{{ __('Select a option') }}</option>
                                                    <option value="Que no sean">{{ __('That they are not') }}</option>
                                                    <option value="Que sean">{{ __('That they are') }}</option>
                                                @else
                                                    <option value="">{{ __('Without conditional') }}</option>
                                                @endif
                                            </select>
                                            <!--end::Input-->
                                            @error('coupon.conditional')
                                                <small class="form-text text-danger"
                                                    role="alert">{{ $message }}</small>
                                            @enderror
                                        </div>
                                        <!--end::Input group-->
                                    </div>
                                </div>
                            </div>
                            <!--end::Input group-->
                            <!--begin::Separator-->
                            <div class="separator"></div>
                            <!--end::Separator-->
                            <!--begin::Search products-->
                            <div class="d-flex align-items-center position-relative mb-n7">
                                <!--begin::Svg Icon | path: icons/duotune/general/gen021.svg-->
                                <span class="svg-icon svg-icon-1 position-absolute ms-4">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none">
                                        <rect opacity="0.5" x="17.0365" y="15.1223" width="8.15546" height="2"
                                            rx="1" transform="rotate(45 17.0365 15.1223)" fill="gray" />
                                        <path
                                            d="M11 19C6.55556 19 3 15.4444 3 11C3 6.55556 6.55556 3 11 3C15.4444 3 19 6.55556 19 11C19 15.4444 15.4444 19 11 19ZM11 5C7.53333 5 5 7.53333 5 11C5 14.4667 7.53333 17 11 17C14.4667 17 17 14.4667 17 11C17 7.53333 14.4667 5 11 5Z"
                                            fill="gray" />
                                    </svg>
                                </span>
                                <!--end::Svg Icon-->
                                <input wire:model.live.debounce.500ms="search" type="search"
                                    class="form-control form-control-solid w-100 w-lg-50 ps-14"
                                    placeholder="{{ __('Search...') }}" />
                            </div>
                            <!--end::Search products-->
                            <!--begin::Table-->
                            <div class="overflow">
                                <table class="table align-middle table-row-dashed fs-6 gy-5 ">
                                    <!--begin::Table head-->
                                    <thead>
                                        <tr class="text-start text-gray-400 fw-bolder fs-7 text-uppercase gs-0">
                                            <th class="w-25px pe-2">{{ __('Choose') }}</th>
                                        </tr>
                                    </thead>
                                    <!--end::Table head-->
                                    <!--begin::Table body-->
                                    <tbody class="fw-bold text-gray-600">
                                        @foreach($models as $model)
                                            <!--begin::Table row-->
                                            <tr>
                                                <!--begin::Checkbox-->
                                                <td>
                                                    <div class="form-check form-check-sm form-check-custom">
                                                        <input wire:model="couponablesArray" class="form-check-input"
                                                            type="checkbox" value="{{ $model->id }}" />
                                                    </div>
                                                </td>
                                                <!--end::Checkbox-->
                                                <!--begin::Product=-->
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <!--begin::Thumbnail-->
                                                        <a target="blank" class="symbol symbol-50px">
                                                            <span class="symbol-label"
                                                                style="background-image:url({{ $model->imagePreview() }});"></span>
                                                        </a>
                                                        <!--end::Thumbnail-->
                                                        <div class="ms-5">
                                                            <!--begin::Title-->
                                                            <a target="blank"
                                                                class="text-gray-800 text-hover-primary fs-5 fw-bolder">{{ $model->name }}</a>
                                                            <!--end::Title-->
                                                        </div>
                                                    </div>
                                                </td>
                                                <!--end::Product=-->
                                            </tr>
                                            <!--end::Table row-->
                                        @endforeach
                                    </tbody>
                                    <!--end::Table body-->
                                </table>
                                <!--end::Table-->
                                @if(count($models))
                                    {{ $models->links() }}
                                @endif
                            </div>
                        </div>
                    </div>
                    <!--end::Card header-->
                </div>
                <!--end::Order details-->
                <!--end::Meta options-->
                <div class="d-flex justify-content-end">
                    <!--begin::Button-->
                    <a href="{{ route('admin.coupon.index') }}" class="btn btn-light me-5">{{ __('Cancel') }}</a>
                    <!--end::Button-->
                    <!--begin::Button-->
                    <button wire:loading.attr="disabled" wire:target="{{ $method }}" type="submit"
                        class="btn btn-primary">
                        <span class="indicator-label">{{ __('Save changes') }}</span>
                        <span wire:loading wire:target="{{ $method }}"
                            class="spinner-border spinner-border-sm align-middle ms-2"></span>
                    </button>
                    <!--end::Button-->
                </div>
            </div>
        </form>
        <!--end::Form-->
    </div>
</div>
