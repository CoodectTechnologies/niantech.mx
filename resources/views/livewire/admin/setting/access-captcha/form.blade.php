<div>
    @include('admin.components.errors')
    <!--begin::Form-->
    <form class="form" wire:submit.prevent="{{ $method }}">
        <div class="card shadow p-3 mb-5 bg-body rounded">
            <div class="card-header">
                <img width="200" src="{{ asset('assets/admin/media/captcha/logo.png') }}" alt="Captcha">
            </div>
            <div class="card-body">
                <div class="fv-row mb-7">
                    <label class="fs-6 fw-bold form-label mb-2">
                        <span class="required">{{ __('Status') }}</span>
                    </label>
                    <select wire:model="captchaStatus"
                        class="form-control form-control-solid @error('captchaStatus') invalid-feedback @enderror">
                        <option value="">{{ __('Select a option') }}</option>
                        <option value="true">{{ __('Active') }}</option>
                        <option value="false">{{ __('Off') }}</option>
                    </select>
                    @error('captchaStatus')
                        <small class="form-text text-danger" role="alert">{{ $message }}</small>
                    @enderror
                </div>
                <!--begin::Input group-->
                <div class="fv-row mb-7">
                    <label class="fs-6 fw-bold form-label mb-2">
                        <span class="">Captcha Public key</span>
                    </label>
                    <input wire:model="captchaPublicKey"
                        class="form-control form-control-solid @error('captchaPublicKey') invalid-feedback @enderror"
                        placeholder="Ejem: 6LdkWNAiAAAAAMTO7SVpxHVCp0VsyZtcfvmiOXXXX" name="" />
                    @error('captchaPublicKey')
                        <small class="form-text text-danger" role="alert">{{ $message }}</small>
                    @enderror
                </div>
                <!--begin::Input group-->
                <div class="fv-row mb-7">
                    <label class="fs-6 fw-bold form-label mb-2">
                        <span class="">Captcha Secret Key</span>
                    </label>
                    <input wire:model="captchaSecretKey"
                        class="form-control form-control-solid @error('captchaSecretKey') invalid-feedback @enderror"
                        placeholder="Ejem: 6LdkWNXXXAAAAFURUtcpJEu7jMZ3Jt92qbxz-XXX" name="" />
                    @error('captchaSecretKey')
                        <small class="form-text text-danger" role="alert">{{ $message }}</small>
                    @enderror
                </div>
            </div>
        </div>
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
