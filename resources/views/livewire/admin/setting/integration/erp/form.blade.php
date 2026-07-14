<div>
    @include('admin.components.errors')
    <!--begin::Form-->
    <form class="form" wire:submit.prevent="{{ $method }}">
        <div class="fv-row mb-7">
            <label class="fs-6 fw-bold form-label mb-2">
                <span class="required">{{ __('Status') }}</span>
            </label>
            <select wire:model="erpStatus"
                class="form-control form-control-solid @error('erpStatus') invalid-feedback @enderror">
                <option value="">{{ __('Select a option') }}</option>
                <option value="true">{{ __('Active') }}</option>
                <option value="false">{{ __('Off') }}</option>
            </select>
            @error('erpStatus')
                <small class="form-text text-danger" role="alert">{{ $message }}</small>
            @enderror
        </div>
        <div class="fv-row mb-7">
            <label class="fs-6 fw-bold form-label mb-2">
                <span class="">API URL</span>
            </label>
            <input wire:model="erpUrl"
                class="form-control form-control-solid @error('erpUrl') invalid-feedback @enderror"
                placeholder="Ejem: https://api.example.com" name="erpUrl" />
            @error('erpUrl')
                <small class="form-text text-danger" role="alert">{{ $message }}</small>
            @enderror
        </div>
        <div class="fv-row mb-7">
            <label class="fs-6 fw-bold form-label mb-2">
                <span class="required">{{ __('Database') }}</span>
            </label>
            <input wire:model="erpDatabase"
                class="form-control form-control-solid @error('erpDatabase') invalid-feedback @enderror"
                placeholder="Ejem: odoo_db" name="erpDatabase" />
            @error('erpDatabase')
                <small class="form-text text-danger" role="alert">{{ $message }}</small>
            @enderror
        </div>
        <div class="fv-row mb-7">
            <label class="fs-6 fw-bold form-label mb-2">
                <span class="required">{{ __('Username') }}</span>
            </label>
            <input wire:model="erpUsername"
                class="form-control form-control-solid @error('erpUsername') invalid-feedback @enderror"
                placeholder="Ejem: admin" name="erpUsername" />
            @error('erpUsername')
                <small class="form-text text-danger" role="alert">{{ $message }}</small>
            @enderror
        </div>
        <div class="fv-row mb-7">
            <label class="fs-6 fw-bold form-label mb-2">
                <span class="required">{{ __('Password / API Key') }}</span>
            </label>
            <input wire:model="erpPassword" type="password"
                class="form-control form-control-solid @error('erpPassword') invalid-feedback @enderror"
                placeholder="••••••••" name="erpPassword" />
            @error('erpPassword')
                <small class="form-text text-danger" role="alert">{{ $message }}</small>
            @enderror
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
        <!--end::Actions-->
    </form>
    <!--end::Form-->
</div>
