<!-- Start of PageContent -->
<div class="page-content pt-2">
    <div class="container">
        <div class="tab tab-vertical row gutter-lg">

            @include('ecommerce.account.menu.index')

            <div class="main-content tab-content mb-6">
                <div class="tab-pane active in" id="account-orders">
                    <div class="icon-box icon-box-side icon-box-light">
                        <span class="icon-box-icon icon-orders">
                            <i class="w-icon-orders"></i>
                        </span>
                        <div class="icon-box-content">
                            <h4 class="icon-box-title text-capitalize ls-normal mb-0">{{ __('Orders') }}</h4>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="shop-table account-orders-table mb-6">
                            <thead>
                                <tr>
                                    <th style="min-width: 100px;" class="order-id">{{ __('Order') }}</th>
                                    <th style="min-width: 100px;" class="order-date">{{ __('Date') }}</th>
                                    <th style="min-width: 100px;" class="order-status">{{ __('Status') }}</th>
                                    <th style="min-width: 100px;" class="order-total">{{ __('Total') }}</th>
                                    {{-- <th style="min-width: 100px;" class="order-actions">{{ __('Actions') }}</th> --}}
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($ordersInternal as $orderInternal)
                                    <tr>
                                        <td class="order-id">#{{ $orderInternal['number'] }}</td>
                                        <td class="order-date">{{ $orderInternal['date'] }}</td>
                                        <td class="order-status">{!! $orderInternal['status'] !!}</td>
                                        <td class="order-total">
                                            <span class="order-price">{!! $orderInternal['total'] !!}</span>
                                        </td>
                                        <td class="order-action">
                                            <a href="{{ route('ecommerce.account.order.show', $orderInternal['number']) }}"
                                                class="btn btn-outline btn-default btn-block btn-sm btn-rounded">{{ __('View') }}</a>
                                        </td>
                                    </tr>
                                @endforeach
                                @foreach($ordersErp as $orderErp)
                                    <tr>
                                        <td class="order-id">#{{ $orderErp['so'] }}</td>
                                        <td class="order-date">{{ $orderErp['date'] }}</td>
                                        <td class="order-status">{{ $orderErp['status'] }}</td>
                                        <td class="order-total">
                                            <span class="order-price">{{ $orderErp['total'] }}
                                                {{ $orderErp['currency'] }}</span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <a href="{{ route('ecommerce.product.index') }}" class="btn btn-dark btn-rounded btn-icon-right">
                        {{ __('Go shop') }}<i class="w-icon-long-arrow-right"></i></a>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End of PageContent -->
