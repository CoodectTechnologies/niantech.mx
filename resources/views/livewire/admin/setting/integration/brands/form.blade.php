<div>
    @include('admin.components.errors')
    <!--begin::Form-->
    <form class="form" wire:submit.prevent="{{ $method }}">
        <div class="fv-row mb-7">
            <label class="fs-6 fw-bold form-label mb-2">
                <span class="required">{{ __('Status') }}</span>
            </label>
            <select wire:model="brandsStatus"
                class="form-control form-control-solid @error('brandsStatus') invalid-feedback @enderror">
                <option value="">{{ __('Select a option') }}</option>
                <option value="true">{{ __('Active') }}</option>
                <option value="false">{{ __('Off') }}</option>
            </select>
            @error('brandsStatus')
                <small class="form-text text-danger" role="alert">{{ $message }}</small>
            @enderror
        </div>
        <div class="fv-row mb-7">
            <label class="fs-6 fw-bold form-label mb-2">
                <span class="">API URL</span>
            </label>
            <input wire:model="brandsUrl"
                class="form-control form-control-solid @error('brandsUrl') invalid-feedback @enderror"
                placeholder="Ejem: pk_test_WUYDZRAxUFDfIhtYShxshVcZ00j9lAoooz" name="" />
            @error('brandsUrl')
                <small class="form-text text-danger" role="alert">{{ $message }}</small>
            @enderror
        </div>
        <div class="fv-row mb-7">
            <label class="fs-6 fw-bold form-label mb-2">
                <span class="">Usuario</span>
            </label>
            <input wire:model="brandsUser"
                class="form-control form-control-solid @error('brandsUser') invalid-feedback @enderror"
                placeholder="Ejem: pk_test_WUYDZRAxUFDfIhtYShxshVcZ00j9lAoooz" name="" />
            @error('brandsUser')
                <small class="form-text text-danger" role="alert">{{ $message }}</small>
            @enderror
        </div>
        <div class="fv-row mb-7">
            <label class="fs-6 fw-bold form-label mb-2">
                <span class="">Contraseña</span>
            </label>
            <input wire:model="brandsPass" type="password"
                class="form-control form-control-solid @error('brandsPass') invalid-feedback @enderror"
                placeholder="Ejem: pk_test_WUYDZRAxUFDfIhtYShxshVcZ00j9lAoooz" name="" />
            @error('brandsPass')
                <small class="form-text text-danger" role="alert">{{ $message }}</small>
            @enderror
        </div>
        <div class="fv-row mb-7">
            <label class="fs-6 fw-bold form-label mb-2">
                <span class="">Marcas permitidas</span>
            </label>
            <input wire:model="brandsAllowed"
                class="form-control form-control-solid @error('brandsAllowed') invalid-feedback @enderror"
                placeholder="Ejem: pk_test_WUYDZRAxUFDfIhtYShxshVcZ00j9lAoooz" name="" />
            @error('brandsAllowed')
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
