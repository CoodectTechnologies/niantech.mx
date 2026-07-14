<div>
    @include('admin.components.errors')
    <!--begin::Form-->
    <form class="form" wire:submit.prevent="{{ $method }}">
        <div class="card shadow p-3 mb-5 bg-body rounded">
            <div class="card-header">
                <img width="200" src="{{ asset('assets/admin/media/google/logo.webp') }}" alt="Google">
            </div>
            <div class="card-body">
                <div class="fv-row mb-7">
                    <label class="fs-6 fw-bold form-label mb-2">
                        <span class="required">{{ __('Status') }}</span>
                    </label>
                    <select wire:model="googleStatus"
                        class="form-control form-control-solid @error('googleStatus') invalid-feedback @enderror">
                        <option value="">{{ __('Select a option') }}</option>
                        <option value="true">{{ __('Active') }}</option>
                        <option value="false">{{ __('Off') }}</option>
                    </select>
                    @error('googleStatus')
                        <small class="form-text text-danger" role="alert">{{ $message }}</small>
                    @enderror
                </div>
                <!--begin::Input group-->
                <div class="fv-row mb-7">
                    <label class="fs-6 fw-bold form-label mb-2">
                        <span class="">Google Client Id</span>
                    </label>
                    <input wire:model="googleClientId"
                        class="form-control form-control-solid @error('googleClientId') invalid-feedback @enderror"
                        placeholder="Ejem: 84940039XXXX-94f975l20bvhte8rvie08kt7f3g04spt.apps.googleusercontent.com"
                        name="" />
                    @error('googleClientId')
                        <small class="form-text text-danger" role="alert">{{ $message }}</small>
                    @enderror
                </div>
                <!--begin::Input group-->
                <div class="fv-row mb-7">
                    <label class="fs-6 fw-bold form-label mb-2">
                        <span class="">Google Secret Key</span>
                    </label>
                    <input wire:model="googleClientSecret"
                        class="form-control form-control-solid @error('googleClientSecret') invalid-feedback @enderror"
                        placeholder="Ejem: GOCSPX--ecd34NVLdqfwDTaiLTW5MXXXXXI" name="" />
                    @error('googleClientSecret')
                        <small class="form-text text-danger" role="alert">{{ $message }}</small>
                    @enderror
                </div>
                <span class="badge badge-secondary">
                    <span class="badge badge-primary">Ruta para login con google:
                        {{ route('login.google.redirect') }}</a>
                    </span>
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
