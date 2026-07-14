<div>
    <div>
        @include('admin.components.errors')
        <!--begin::Form-->
        <form class="form" wire:submit.prevent="{{ $method }}">
            <!--begin::Input group-->
            <div class="fv-row mb-7">
                <label class="fs-6 fw-bold form-label mb-2">
                    <span class="required">{{ __('Name') }} SAT</span>
                </label>
                <input type="text" required wire:model="unitType.name_sat"
                    class="form-control form-control-solid @error('unitType.name_sat') invalid-feedback @enderror"
                    placeholder="Piece" name="" />
                @error('unitType.name_sat')
                    <small class="form-text text-danger" role="alert">{{ $message }}</small>
                @enderror
            </div>
            <!--end::Input group-->
            <!--begin::Input group-->
            <div class="fv-row mb-7">
                <label class="fs-6 fw-bold form-label mb-2">
                    <span class="required">{{ __('Name') }}</span>
                </label>
                <input type="text" required wire:model="unitType.name"
                    class="form-control form-control-solid @error('unitType.name') invalid-feedback @enderror"
                    placeholder="Pieza" name="" />
                @error('unitType.name')
                    <small class="form-text text-danger" role="alert">{{ $message }}</small>
                @enderror
            </div>
            <!--end::Input group-->
            <!--begin::Input group-->
            <div class="fv-row mb-7">
                <label class="fs-6 fw-bold form-label mb-2">
                    <span class="required">{{ __('Code') }} SAT</span>
                </label>
                <input type="text" required wire:model="unitType.code"
                    class="form-control form-control-solid @error('unitType.code') invalid-feedback @enderror"
                    placeholder="H87" name="" />
                @error('unitType.code')
                    <small class="form-text text-danger" role="alert">{{ $message }}</small>
                @enderror
            </div>
            <!--end::Input group-->
            <!--begin::Input group-->
            <div class="fv-row mb-7">
                <label class="fs-6 fw-bold form-label mb-2">
                    <span class="required">{{ __('Description') }}</span>
                </label>
                <input type="text" required wire:model="unitType.description"
                    class="form-control form-control-solid @error('unitType.description') invalid-feedback @enderror"
                    placeholder="Unidad utilizada para contar objetos individuales." name="" />
                @error('unitType.description')
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
</div>
