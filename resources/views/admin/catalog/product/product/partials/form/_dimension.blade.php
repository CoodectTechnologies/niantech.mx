<div class="card card-flush py-4">
    <!--begin::Card header-->
    <div class="card-header">
        <div class="card-title">
            <h2>{{ __('Dimensions') }}</h2>
        </div>
    </div>
    <!--end::Card header-->
    <!--begin::Card body-->
    <div class="card-body pt-0">
        <!--begin::Shipping form-->
        <div class="mt-10">
            <!--begin::Input group-->
            <div class="mb-0 fv-row">
                <!--begin::Label-->
                <label class="form-label">{{ __('Unit type') }}</label>
                <!--end::Label-->
                <!--begin::Editor-->
                <select wire:model="product.unit_type_id"
                    class="form-control form-control-sm mb-2 @error('product.unit_type_id') invalid-feedback @enderror"
                    placeholder="Ejem: 2">
                    <option value="">{{ __('Select a option') }}</option>
                    @foreach($unitTypes as $unitType)
                        <option value="{{ $unitType->id }}">{{ $unitType->name }}</option>
                    @endforeach
                </select>
                <!--end::Editor-->
                @error('product.weight_kl')
                    <small class="form-text text-danger" role="alert">{{ $message }}</small>
                @enderror
                <!--begin::Description-->
                <div class="text-muted fs-7">{{ __('Establish product weight in kilograms') }} (kg)</div>
                <!--end::Description-->
            </div>
            <!--end::Input group-->
            <!--begin::Input group-->
            <div class="mb-0 fv-row">
                <!--begin::Label-->
                <label class="form-label">{{ __('Weight') }} (KG)</label>
                <!--end::Label-->
                <!--begin::Editor-->
                <input wire:model="product.weight_kl" type="number" step="0.01" pattern="^\d*(\.\d{0,2})?$"
                    name="weight_kl" class="form-control form-control-sm mb-2 @error('product.weight_kl') invalid-feedback @enderror"
                    placeholder="Ejem: 2" />
                <!--end::Editor-->
                @error('product.weight_kl')
                    <small class="form-text text-danger" role="alert">{{ $message }}</small>
                @enderror
                <!--begin::Description-->
                <div class="text-muted fs-7">{{ __('Establish product weight in kilograms') }} (kg)</div>
                <!--end::Description-->
            </div>
            <!--end::Input group-->
            <!--begin::Input group-->
            <!--end::Input group-->
            <!--begin::Input group-->
            <div class="fv-row">
                <!--begin::Label-->
                <label class="form-label">{{ __('Dimesions') }}</label>
                <!--end::Label-->
                <!--begin::Input-->
                <div class="d-flex flex-wrap flex-sm-nowrap gap-3">
                    <input wire:model="product.width" type="number" step="0.01" pattern="^\d*(\.\d{0,2})?$"
                        name="width" class="form-control form-control-sm mb-2" placeholder="{{ __('Width') }} (CM)" />
                    @error('product.width')
                        <small class="form-text text-danger" role="alert">{{ $message }}</small>
                    @enderror
                    <input wire:model="product.height" type="number" step="0.01" pattern="^\d*(\.\d{0,2})?$"
                        name="height" class="form-control form-control-sm mb-2" placeholder="{{ __('height') }} (CM)" />
                    @error('product.height')
                        <small class="form-text text-danger" role="alert">{{ $message }}</small>
                    @enderror
                    <input wire:model="product.length" type="number" step="0.01" pattern="^\d*(\.\d{0,2})?$"
                        name="length" class="form-control form-control-sm mb-2" placeholder="{{ __('length') }} (CM)" />
                    @error('product.length')
                        <small class="form-text text-danger" role="alert">{{ $message }}</small>
                    @enderror
                </div>
                <!--end::Input-->
                <!--begin::Description-->
                <div class="text-muted fs-7">{{ __('Enter product dimensions in centimeters') }} (cm).</div>
                <!--end::Description-->
            </div>
            <!--end::Input group-->
        </div>
        <!--end::Shipping form-->
    </div>
    <!--end::Card header-->
</div>
