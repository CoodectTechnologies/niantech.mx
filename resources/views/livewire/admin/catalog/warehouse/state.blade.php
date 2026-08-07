<div>
    @include('admin.components.errors')
    <!--begin::Form-->
    <form wire:submit.prevent="{{ $method }}" class="form d-flex flex-column flex-lg-row">
        <!--begin::Main column-->
        <div class="d-flex flex-column flex-lg-row-fluid">
            <!--begin::Order details-->
            <div class="card card-flush py-4">
                <!--begin::Card header-->
                <div class="card-header">
                    <div class="card-title">
                        <h2>{{ __('General data') }}</h2>
                    </div>
                    <div class="card-title">
                        <!--begin::Select2-->
                        <select wire:model.live="countryId" wire:change="loadWarehouseState"
                            class="form-select form-select-solid">
                            @foreach($countries as $country)
                                <option value="{{ $country->id }}">{{ $country->name }}</option>
                            @endforeach
                        </select>
                        <!--end::Select2-->
                    </div>
                </div>
                <!--end::Card header-->

                <!--begin::Card body-->
                <div class="card-body pt-0">
                    @if(isset($warehouseStateArray['states']))
                        <ul>
                            @foreach($warehouseStateArray['states'] as $stateId => $state)
                                <li>
                                    {{ __('State') }}: {{ $state['name'] }}
                                </li>
                                <ul>
                                    @foreach($state['productWarehouses'] as $productWarehouseId => $productWarehouse)
                                        <li>
                                            {{ __('Warehouse') }}: {{ $productWarehouse['name'] }}
                                            <input
                                                wire:model="warehouseState.states.{{ $stateId }}.productWarehouses.{{ $productWarehouseId }}.priority"
                                                type="number" placeholder="Nivel de prioridad">
                                        </li>
                                    @endforeach
                                </ul>
                            @endforeach
                        </ul>
                    @endif
                </div>
                <!--end::Card header-->
            </div>
            <!--end::Order details-->
            <!--end::Meta options-->
            <div class="d-flex justify-content-end">
                <!--begin::Button-->
                <a href="{{ route('admin.catalog.warehouse.index') }}"
                    class="btn btn-light me-5">{{ __('Cancel') }}</a>
                <!--end::Button-->
                <!--begin::Button-->
                <button wire:loading.attr="disabled" wire:target="{{ $method }}" type="submit"
                    class="btn btn-primary">
                    <span class="indicator-label">{{ __('Save changes') }}</span>
                    <span wire:loading wire:target="{{ $method }}"
                        class="spinner-border spinner-border-sm align-middle ms-2"></span>
                </button>
                <!--end::Button-->
            </div>
        </div>
        <!--end::Main column-->
    </form>
    <!--end::Form-->
</div>
