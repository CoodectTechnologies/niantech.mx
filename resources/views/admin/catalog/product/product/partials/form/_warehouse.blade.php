<div class="card card-flush py-4">
    <!--begin::Card header-->
    <div class="card-header">
        <div class="card-title">
            <h2>{{ __('Warehouse') }}</h2>
        </div>
    </div>
    <!--end::Card header-->
    <!--begin::Card body-->
    <div class="card-body pt-0">
        <!--end::Input group-->
        <div class="row">
            @foreach($warehouses as $warehouse)
                <!--begin::Input group-->
                <div class="mb-0 fv-row col-lg-6">
                    <!--begin::Label-->
                    <label class="form-label">{{ $warehouse->name }}</label>
                    <!--end::Label-->
                    <!--begin::Input-->
                    <input wire:model="catalogProductWarehousesArray.{{ $warehouse->id }}" type="number"
                        class="form-control form-control-sm mb-2 @error('catalogProductWarehousesArray.{{ $warehouse->id }}') invalid-feedback @enderror"
                        placeholder="Cantidad de stock del almacen" />
                    <!--end::Input-->
                    @error('catalogProductWarehousesArray.{{ $warehouse->id }}')
                        <small class="form-text text-danger" role="alert">{{ $message }}</small>
                    @enderror
                </div>
                <!--end::Input group-->
            @endforeach
        </div>
        <!--begin::Input group-->
    </div>
    <!--end::Card header-->
</div>
