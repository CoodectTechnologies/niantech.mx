<div>
    <form class="form" wire:submit.prevent="save">
        <div class="fv-row mb-7">
            <label class="fs-6 fw-bold form-label mb-2">
                <span class="required">{{ __('Nombre de la caracteristica') }}</span>
            </label>
            <input wire:model="translations.name.{{ translatable() }}" class="form-control form-control-solid @error("translations.name.".translatable()) is-invalid @enderror"/>
            @error("translations.name.".translatable()) <small class="form-text text-danger" role="alert">{{ $message }}</small> @enderror
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
