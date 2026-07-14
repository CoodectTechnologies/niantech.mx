<div>
    <div class="table-responsive">
        <table class="table align-middle table-row-dashed fs-6 gy-5">
            <!--begin::Table head-->
            <thead>
                <!--begin::Table row-->
                <tr class="text-start text-muted fw-bolder fs-7 text-uppercase gs-0">
                    <th class="">{{ __('Provider') }}</th>
                    <th class="">{{ __('Guide') }}</th>
                    <th class="">{{ __('Actions') }}</th>
                </tr>
                <!--end::Table row-->
            </thead>
            <!--end::Table head-->
            <!--begin::Table body-->
            <tbody class="text-gray-600 fw-bold">
                @forelse ($orderProviders as $orderProvider)
                    <!--begin::Table row-->
                    <tr>
                        <td>
                            <span class="badge badge-primary">{{ $orderProvider->provider }}
                                {{ $orderProvider->provider_id }}</span>
                        </td>
                        <td>
                            @if($orderProvider->provider_guide)
                                <a href="{{ Storage::url($orderProvider->provider_guide) }}" target="_blank"
                                    rel="noopener noreferrer">
                                    <img width="50" src="{{ asset('assets/admin/media/icons/pdf.png') }}"
                                        alt="">
                                </a>
                            @else
                                <span class="badge badge-secondary">{{ __('Missing guide') }}</span>
                            @endif
                        </td>
                        <!--begin::Action=-->
                        <td class="d-flex">
                            <div class="dropdown">
                                <a class="btn btn-light-primary dropdown-toggle" type="button"
                                    id="dropdownMenuActions-{{ $orderProvider->id }}" data-bs-toggle="dropdown"
                                    aria-expanded="false">
                                    <!--begin::Svg Icon | path: icons/duotune/general/gen019.svg-->
                                    <span class="svg-icon svg-icon-3">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none">
                                            <path
                                                d="M17.5 11H6.5C4 11 2 9 2 6.5C2 4 4 2 6.5 2H17.5C20 2 22 4 22 6.5C22 9 20 11 17.5 11ZM15 6.5C15 7.9 16.1 9 17.5 9C18.9 9 20 7.9 20 6.5C20 5.1 18.9 4 17.5 4C16.1 4 15 5.1 15 6.5Z"
                                                fill="gray" />
                                            <path opacity="0.3"
                                                d="M17.5 22H6.5C4 22 2 20 2 17.5C2 15 4 13 6.5 13H17.5C20 13 22 15 22 17.5C22 20 20 22 17.5 22ZM4 17.5C4 18.9 5.1 20 6.5 20C7.9 20 9 18.9 9 17.5C9 16.1 7.9 15 6.5 15C5.1 15 4 16.1 4 17.5Z"
                                                fill="gray" />
                                        </svg>
                                    </span>
                                    <!--end::Svg Icon-->
                                    {{ __('Actions') }} <span wire:loading wire:target="exportProducts"
                                        class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                </a>
                                <ul class="dropdown-menu"
                                    aria-labelledby="dropdownMenuActions-{{ $orderProvider->id }}">
                                    <li><a data-bs-toggle="modal"
                                            data-bs-target="#order_providers_guide_{{ $orderProvider->id }}"
                                            class="dropdown-item">{{ __('Attach guide') }}</a></li>
                                    <li><a data-bs-toggle="modal"
                                            data-bs-target="#order_providers_show_{{ $orderProvider->id }}"
                                            class="dropdown-item">{{ __('Show supplier information') }}</a></li>
                                </ul>
                            </div>

                            @livewire('admin.order.provider.guide', ['orderProvider' => $orderProvider], key('order-provider-guide-' . $orderProvider->id))

                        </td>
                        <!--end::Action=-->
                    </tr>
                    <!--end::Table row-->
                @empty
                    <div class="alert alert-warning">
                        {{-- <p>{{ __('Without order provider, contact us for soport') }}</p> --}}
                        <p>Si el status del pago esta en pendiente: Quiere decir que no se ha enviado el pedido a
                            proveedor</p>
                        <p>Si el pago fue mediante transferencia o depósito bancario y ya ha corroborado el pago, puede
                            cambiarlo a "Aprobado", y de esta manera se enviará el pedido automaticamente a proveedor
                            (Puede tardar unos minutos)</p>
                    </div>
                @endforelse
            </tbody>
            <!--end::Table body-->
        </table>
        <table class="table align-middle table-row-dashed fs-6 gy-5">
            <!--begin::Table head-->
            <thead>
                <!--begin::Table row-->
                <tr class="text-start text-muted fw-bolder fs-7 text-uppercase gs-0">
                    <th class="">{{ __('Product') }}</th>
                    <th class="">{{ __('SKU') }}</th>
                    <th class="">{{ __('Quantity') }}</th>
                </tr>
                <!--end::Table row-->
            </thead>
            <!--end::Table head-->
            <!--begin::Table body-->
            <tbody class="text-gray-600 fw-bold">
                @foreach($orderProductWarehouses as $orderProductWarehouse)
                    <!--begin::Table row-->
                    <tr>
                        <td>
                            <a href="{{ route('admin.catalog.product.show', $orderProductWarehouse->orderProduct->product) }}"
                                target="_blank" rel="noopener noreferrer">
                                {{ $orderProductWarehouse->orderProduct->product->name }}
                            </a>
                        </td>
                        <td>{{ $orderProductWarehouse->orderProduct->product->sku }}</td>
                        <td>{{ $orderProductWarehouse->quantity }}</td>
                    </tr>
                    <!--end::Table row-->
                @endforeach
            </tbody>
            <!--end::Table body-->
        </table>
    </div>
</div>
