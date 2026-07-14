<div>
    <div class="row pb-5">
        @if(config('services.odoo.status'))
            <div class="col-xl-6 mb-5 mb-xl-10">
                <!--begin::Table Widget 4-->
                <div class="card card-flush h-xl-100">
                    <!--begin::Card header-->
                    <div class="card-header pt-7">
                        <!--begin::Title-->
                        <h3 class="card-title align-items-start flex-column">
                            <span class="card-label fw-bolder text-dark">{{ __('Orders without procesing') }}</span>
                            <span class="text-gray-400 mt-1 fw-bold fs-6">{{ count($ordersWitoutProcesing) }}
                                {{ __('orders') }}</span>
                        </h3>
                        <!--end::Title-->
                    </div>
                    <!--end::Card header-->
                    <!--begin::Card body-->
                    <div class="card-body">
                        <!--begin::Table-->
                        <div class="table-responsive">
                            <table class="table align-middle table-row-dashed fs-6 gy-3">
                                <!--begin::Table head-->
                                <thead>
                                    <!--begin::Table row-->
                                    <tr class="text-start text-gray-400 fw-bolder fs-7 text-uppercase gs-0">
                                        <th class="min-w-100px">{{ __('Number') }}</th>
                                        <th class="min-w-100px">{{ __('Provider') }}</th>
                                        <th class="min-w-100px">{{ __('Date') }}</th>
                                        <th class="min-w-100px">{{ __('Total') }}</th>
                                        <th class="min-w-100px">{{ __('Payment status') }}</th>
                                    </tr>
                                    <!--end::Table row-->
                                </thead>
                                <!--end::Table head-->
                                <!--begin::Table body-->
                                <tbody class="fw-bolder text-gray-600">
                                    @foreach($ordersWitoutProcesing as $orderWitoutProcesing)
                                        <tr>
                                            <td class=""><a
                                                    href="{{ route('admin.order.show', $orderWitoutProcesing) }}">{{ $orderWitoutProcesing->number }}</a>
                                            </td>
                                            <td class="">
                                                @foreach($orderWitoutProcesing->getProvidersCode() as $code)
                                                    <span class="badge badge-primary">{{ $code }}</span>
                                                @endforeach
                                            </td>
                                            <td class="">{{ $orderWitoutProcesing->created_at }}</td>
                                            <td class=""><span
                                                    class="text-gray-800 fw-bolder">{{ $orderWitoutProcesing->totalToString() }}</span>
                                            </td>
                                            <td class="">{!! $orderWitoutProcesing->paymentStatusToString() !!}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <!--end::Table body-->
                            </table>
                        </div>
                        <!--end::Table-->
                    </div>
                    <!--end::Card body-->
                </div>
                <!--end::Table Widget 4-->
            </div>
        @endif
        @if(!config('services.odoo.status'))
            <div class="col-xl-6 mb-5 mb-xl-10">
                <!--begin::Table Widget 4-->
                <div class="card card-flush h-xl-100">
                    <!--begin::Card header-->
                    <div class="card-header pt-7">
                        <!--begin::Title-->
                        <h3 class="card-title align-items-start flex-column">
                            <span class="card-label fw-bolder text-dark">{{ __('Orders without payment') }}</span>
                            <span class="text-gray-400 mt-1 fw-bold fs-6">{{ count($ordersWitoutPayment) }}
                                {{ __('orders') }}</span>
                        </h3>
                        <!--end::Title-->
                    </div>
                    <!--end::Card header-->
                    <!--begin::Card body-->
                    <div class="card-body">
                        <!--begin::Table-->
                        <div class="table-responsive">
                            <table class="table align-middle table-row-dashed fs-6 gy-3">
                                <!--begin::Table head-->
                                <thead>
                                    <!--begin::Table row-->
                                    <tr class="text-start text-gray-400 fw-bolder fs-7 text-uppercase gs-0">
                                        <th class="min-w-100px">{{ __('Number') }}</th>
                                        <th class="min-w-100px">{{ __('Date') }}</th>
                                        <th class="min-w-100px">{{ __('Total') }}</th>
                                        <th class="min-w-100px">{{ __('Payment status') }}</th>
                                    </tr>
                                    <!--end::Table row-->
                                </thead>
                                <!--end::Table head-->
                                <!--begin::Table body-->
                                <tbody class="fw-bolder text-gray-600">
                                    @foreach($ordersWitoutPayment as $orderWitoutPayment)
                                        <tr>
                                            <td class=""><a
                                                    href="{{ route('admin.order.show', $orderWitoutPayment) }}">{{ $orderWitoutPayment->number }}</a>
                                            </td>
                                            <td class="">{{ $orderWitoutPayment->created_at }}</td>
                                            <td class=""><span
                                                    class="text-gray-800 fw-bolder">{{ $orderWitoutPayment->totalToString() }}</span>
                                            </td>
                                            <td class="">{!! $orderWitoutPayment->paymentStatusToString() !!}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <!--end::Table body-->
                            </table>
                        </div>
                        <!--end::Table-->
                    </div>
                    <!--end::Card body-->
                </div>
                <!--end::Table Widget 4-->
            </div>
        @endif
    </div>
    @if(config('services.odoo.status'))
        <div class="row pb-5">
            <div class="col-xl-12 mb-5 mb-xl-10">
                <!--begin::Table Widget 4-->
                <div class="card card-flush h-xl-100">
                    <!--begin::Card header-->
                    <div class="card-header pt-7">
                        <!--begin::Title-->
                        <h3 class="card-title align-items-start flex-column">
                            <span class="card-label fw-bolder text-dark">{{ __('Syncronitations') }}</span>
                        </h3>
                        <!--end::Title-->
                    </div>
                    <!--end::Card header-->
                    <!--begin::Card body-->
                    <div class="card-body">
                        <h3 class="card-title">Catálogo</h3>
                        <ul>
                            <li class="d-flex justify-content-between align-items-center mb-5">
                                {{ __('Create and update products') }} (Todos los dias a las 12am)
                                <button wire:click="createAndUpdateProducts" wire:loading.attr="disabled" type="button"
                                    class="btn btn-primary">
                                    <span class="indicator-label">{{ __('Sincronizar manualmente') }}</span>
                                    <span wire:loading wire:target="createAndUpdateProducts"
                                        class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                </button>
                            </li>
                            <li class="d-flex justify-content-between align-items-center mb-5">
                                {{ __('Update status products') }} (Todos los dias a las 12am)
                                <button wire:click="updateStatusProducts" wire:loading.attr="disabled" type="button"
                                    class="btn btn-primary">
                                    <span class="indicator-label">{{ __('Sincronizar manualmente') }}</span>
                                    <span wire:loading wire:target="updateStatusProducts"
                                        class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                </button>
                            </li>
                            <li class="d-flex justify-content-between align-items-center mb-5">
                                {{ __('Precios') }} (Cada 2 horas)
                                <button wire:click="updatePriceProducts" wire:loading.attr="disabled" type="button"
                                    class="btn btn-primary">
                                    <span class="indicator-label">{{ __('Sincronizar manualmente') }}</span>
                                    <span wire:loading wire:target="updatePriceProducts"
                                        class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                </button>
                            </li>
                            <li class="d-flex justify-content-between align-items-center mb-5">
                                {{ __('Almacenes (Stock)') }} (Cada 10 minutos)
                                <button wire:click="updateWarehouseProducts" wire:loading.attr="disabled" type="button"
                                    class="btn btn-primary">
                                    <span class="indicator-label">{{ __('Sincronizar manualmente') }}</span>
                                    <span wire:loading wire:target="updateWarehouseProducts"
                                        class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                </button>
                            </li>
                            <li class="d-flex justify-content-between align-items-center mb-5">
                                {{ __('Contenido') }} (Todos los días a las 12am)
                                <button wire:click="updateContent" wire:loading.attr="disabled" type="button"
                                    class="btn btn-primary">
                                    <span class="indicator-label">{{ __('Sincronizar manualmente') }}</span>
                                    <span wire:loading wire:target="updateContent"
                                        class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                </button>
                            </li>
                        </ul>
                        <h3 class="card-title">Ordenes</h3>
                        <ul>
                            <li class="d-flex justify-content-between align-items-center mb-5">
                                {{ __('Ordenes') }} (Cada 10 minutos)
                                <button wire:click="syncOrders" wire:loading.attr="disabled" type="button"
                                    class="btn btn-primary">
                                    <span class="indicator-label">{{ __('Sincronizar manualmente') }}</span>
                                    <span wire:loading wire:target="syncOrders"
                                        class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                </button>
                            </li>
                        </ul>
                        <h3 class="card-title">Tipo de cambio</h3>
                        <ul>
                            <li class="d-flex justify-content-between align-items-center mb-5">
                                {{ __('Exchange rate') }} (Todos los dias a las 12am)
                                <button wire:click="syncExchangeRate" wire:loading.attr="disabled" type="button"
                                    class="btn btn-primary">
                                    <span class="indicator-label">{{ __('Sincronizar manualmente') }}</span>
                                    <span wire:loading wire:target="syncExchangeRate"
                                        class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                </button>
                            </li>
                        </ul>
                    </div>
                    <!--end::Card body-->
                </div>
                <!--end::Table Widget 4-->
            </div>
        </div>
    @endif
</div>
