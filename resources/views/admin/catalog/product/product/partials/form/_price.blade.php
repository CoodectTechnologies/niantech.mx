<div class="card card-flush py-4">
    <!--begin::Card header-->
    <div class="card-header">
        <div class="card-title">
            <h2>{{ __('Prices') }}</h2>
        </div>
    </div>
    <!--end::Card header-->
    <!--begin::Card body-->
    <div class="card-body pt-0">
        <div class="row">
            <!--begin::Input group-->
            <div class="mb-0 fv-row col-lg-6">
                <label class="required form-label">
                    {{ __('Price') }}
                    <i class="fa fa-info-circle text-muted" data-bs-toggle="tooltip" title="Precio base del producto"></i>
                </label>
                <div class="input-group input-group-sm mb-2">
                    <span class="input-group-text">{{ $currencySymbol ?? '$' }}</span>
                    <input wire:model="product.price" required type="number" step="0.01"
                        class="form-control form-control-sm @error('product.price') is-invalid @enderror"
                        placeholder="{{ __('Price') }}" />
                </div>
                @error('product.price')
                    <small class="form-text text-danger" role="alert"><i class="fa fa-exclamation-circle"></i> {{ $message }}</small>
                @enderror
            </div>
            <!--end::Input group-->
            <!--begin::Input group-->
            <div class="mb-0 fv-row col-lg-6">
                <label class="form-label">
                    {{ __('Price promotion') }}
                    <i class="fa fa-info-circle text-muted" data-bs-toggle="tooltip" title="Precio especial en promoción, (Aplica solo cuando no tiene variantes)"></i>
                </label>
                <div class="input-group input-group-sm mb-2">
                    <span class="input-group-text">{{ $currencySymbol ?? '$' }}</span>
                    <input wire:model="product.price_promotion" type="number" step="0.01"
                        class="form-control form-control-sm @error('product.price_promotion') is-invalid @enderror"
                        placeholder="{{ __('Price promotion') }}" />
                </div>
                @error('product.price_promotion')
                    <small class="form-text text-danger" role="alert"><i class="fa fa-exclamation-circle"></i> {{ $message }}</small>
                @enderror
            </div>
            <!--end::Input group-->
            <!--begin::Input group-->
            <div class="mb-0 fv-row col-lg-6">
                <!--begin::Label-->
                <label class="form-label">{{ __('Cost') }}</label>
                <!--end::Label-->
                <!--begin::Input-->
                <input wire:model="product.cost" type="number" step="0.01"
                    class="form-control form-control-sm mb-2 @error('product.cost') invalid-feedback @enderror"
                    placeholder="{{ __('Cost') }}" />
                <!--end::Input-->
                @error('product.cost')
                    <small class="form-text text-danger" role="alert">{{ $message }}</small>
                @enderror
            </div>
            <!--end::Input group-->
            <!--begin::Input group-->
            <div class="mb-0 fv-row col-lg-6">
                <!--begin::Label-->
                <label class="required form-label">{{ __('Currency') }}</label>
                <!--end::Label-->
                <select wire:model="product.currency_id" required
                    class="form-control form-control-sm mb-2 @error('product.currency_id') invalid-feedback @enderror">
                    <option value="">{{ __('Select a option') }}</option>
                    @foreach($currencies as $currency)
                        <option value="{{ $currency->id }}">{{ $currency->name }} ({{ $currency->code }})</option>
                    @endforeach
                </select>
                @error('product.currency_id')
                    <small class="form-text text-danger" role="alert">{{ $message }}</small>
                @enderror
            </div>
            <!--end::Input group-->
        </div>
    </div>
    <!--end::Card header-->
</div>
