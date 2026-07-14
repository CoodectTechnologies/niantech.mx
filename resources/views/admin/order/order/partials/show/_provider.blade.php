@php
    $getWarehousesGroup = $this->getWarehousesGroup();
    if(count($getWarehousesGroup)):
@endphp
    <!--begin::Orders-->
    <div class="d-flex flex-column gap-7 gap-lg-10">
        <div class="row">
            <div class="col-lg-8">
                <!-- Orders providers errors -->
                @livewire('admin.order.provider.error', ['order' => $order], key('order-provider-warehouse-error'))
                <!-- Orders providers success -->
                @foreach($getWarehousesGroup as $productWarehouse)
                    <div class="card card-flush my-4 flex-row-fluid">
                        <div class="card-header">
                            <div class="card-title">
                                <h2>{{ __('Warehouse') }} {{ $productWarehouse->name }}</h2>
                            </div>
                        </div>
                        <div class="card-body pt-0">
                            @livewire('admin.order.provider.index', ['order' => $order, 'productWarehouse' => $productWarehouse], key('order-provider-warehouse-'.$productWarehouse->id))
                        </div>
                    </div>
                @endforeach
            </div>
            @if(config('services.erp.status'))
                <div class="col-lg-4">
                    <!--begin::Order history-->
                    <div class="card card-flush my-4 flex-row-fluid">
                        <!--begin::Card header-->
                        <div class="card-header">
                            <div class="card-title">
                                <h2>{{ __('Payment receipt') }} PCH</h2>
                            </div>
                        </div>
                        <!--end::Card header-->
                        <!--begin::Card body-->
                        <div class="card-body pt-0">
                            @livewire('admin.order.provider.payment', ['order' => $order], key('order-provider-payment'))
                        </div>
                        <!--end::Card body-->
                    </div>
                    <!--end::Order history-->
                </div>
            @endif

        </div>
    </div>
    <!--end::Orders-->
@php
    endif;
@endphp
