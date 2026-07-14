
<div class="card mb-4 shadow-sm">
    <div class="card-header bg-dark text-white">
        <i class="fa fa-toggle-on fa-2x me-2"></i>
        <span>{{ __('Estado') }}</span>
    </div>
    <div class="card-body">
        <select required wire:model="product.status" class="form-select mb-2 @error('product.status') invalid-feedback @enderror">
            <option value="Publicado">{{ __('Publicado') }}</option>
            <option value="Borrador">{{ __('Borrador') }}</option>
        </select>
        @error('product.status')
            <small class="form-text text-danger" role="alert">{{ $message }}</small>
        @enderror
    </div>
</div>
