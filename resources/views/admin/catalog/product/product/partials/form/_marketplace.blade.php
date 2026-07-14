
<div class="card mb-4">
    <div class="card-header d-flex align-items-center">
        <i class="fa fa-store fa-2x me-2"></i>
        <span>{{ __('Marketplaces') }}</span>
    </div>
    <div class="card-body">
        <div class="mb-2">
            <label class="form-label">Amazon</label>
            <input wire:model="product.link_amazon" type="text" class="form-control" placeholder="URL de Amazon">
            @error('product.link_amazon')
                <small class="form-text text-danger" role="alert">{{ $message }}</small>
            @enderror
        </div>
        <div class="mb-2">
            <label class="form-label">MercadoLibre</label>
            <input wire:model="product.link_mercadolibre" type="text" class="form-control" placeholder="URL de MercadoLibre">
            @error('product.link_mercadolibre')
                <small class="form-text text-danger" role="alert">{{ $message }}</small>
            @enderror
        </div>
    </div>
</div>
