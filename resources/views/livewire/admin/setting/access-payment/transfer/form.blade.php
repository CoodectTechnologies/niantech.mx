<div>
    @include('admin.components.errors')
    <!--begin::Form-->
    <form class="form" wire:submit.prevent="{{ $method }}">
        <div class="fv-row mb-7">
            <label class="fs-6 fw-bold form-label mb-2">
                <span class="required">{{ __('Status') }}</span>
            </label>
            <select wire:model="paymentStatus"
                class="form-control form-control-solid @error('paymentStatus') invalid-feedback @enderror">
                <option value="">{{ __('Select a option') }}</option>
                <option value="true">{{ __('Active') }}</option>
                <option value="false">{{ __('Off') }}</option>
            </select>
            @error('paymentStatus')
                <small class="form-text text-danger" role="alert">{{ $message }}</small>
            @enderror
        </div>
        <!--begin::Input group-->
        @if(config('services.erp.status'))
            <div class="fv-row mb-7">
                <label class="fs-6 fw-bold form-label mb-2">
                    <span class="required">ERP ID</span>
                </label>
                <input wire:model="paymentErpId"
                    class="form-control form-control-solid @error('paymentErpId') invalid-feedback @enderror"
                    placeholder="Ejem: BBVA" name="" />
                @error('paymentErpId')
                    <small class="form-text text-danger" role="alert">{{ $message }}</small>
                @enderror
            </div>
        @endif
        <div class="fv-row mb-7">
            <label class="fs-6 fw-bold form-label mb-2">
                <span class="required">{{ __('Bank name') }}</span>
            </label>
            <input wire:model="paymentBank"
                class="form-control form-control-solid @error('paymentBank') invalid-feedback @enderror"
                placeholder="Ejem: BBVA" name="" />
            @error('paymentBank')
                <small class="form-text text-danger" role="alert">{{ $message }}</small>
            @enderror
        </div>
        <!--end::Input group-->
        <!--begin::Input group-->
        <div class="fv-row mb-7">
            <label class="fs-6 fw-bold form-label mb-2">
                <span class="">{{ __('Bank account') }}</span>
            </label>
            <input wire:model="paymentAccountBank"
                class="form-control form-control-solid @error('paymentAccountBank') invalid-feedback @enderror"
                placeholder="Ejem: 1519462883 " name="" />
            @error('paymentAccountBank')
                <small class="form-text text-danger" role="alert">{{ $message }}</small>
            @enderror
        </div>
        <!--end::Input group-->
        <!--begin::Input group-->
        <div class="fv-row mb-7">
            <label class="fs-6 fw-bold form-label mb-2">
                <span class="">{{ __('Card') }}</span>
            </label>
            <input wire:model="paymentTarget"
                class="form-control form-control-solid @error('paymentTarget') invalid-feedback @enderror"
                placeholder="Ejem: 4152 3138 0116 5726" name="" />
            @error('paymentTarget')
                <small class="form-text text-danger" role="alert">{{ $message }}</small>
            @enderror
        </div>
        <!--end::Input group-->
        <!--begin::Input group-->
        <div class="fv-row mb-7">
            <label class="fs-6 fw-bold form-label mb-2">
                <span class="">{{ __('To name') }}</span>
            </label>
            <input wire:model="paymentName"
                class="form-control form-control-solid @error('paymentName') invalid-feedback @enderror"
                placeholder="{{ __("Beneficiary's name") }}" name="" />
            @error('paymentName')
                <small class="form-text text-danger" role="alert">{{ $message }}</small>
            @enderror
        </div>
        <!--end::Input group-->
        <!--begin::Actions-->
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
        <!--end::Actions-->
    </form>
    <!--end::Form-->
</div>
