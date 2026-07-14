<!-- Start of PageContent -->
<div class="page-content pt-2">
    <div class="container">
        <div class="tab tab-vertical row gutter-lg">

            @include('ecommerce.account.menu.index')

            <div class="main-content tab-content mb-6">
                <div class="tab-pane active in" id="account-rmas">
                    <div class="icon-box icon-box-side icon-box-light">
                        <span class="icon-box-icon icon-rmas">
                            <i class="w-icon-chat"></i>
                        </span>
                        <div class="icon-box-content">
                            <h4 class="icon-box-title text-capitalize ls-normal mb-0">{{ __('Product regsitration') }}
                            </h4>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="shop-table account-rma-table mb-6">
                            <thead>
                                <tr>
                                    <th style="min-width: 100px;">{{ __('Folio') }}</th>
                                    <th style="min-width: 100px;">{{ __('SKU') }}</th>
                                    <th style="min-width: 100px;">{{ __('Serial number') }}</th>
                                    <th style="min-width: 100px;">{{ __('Product type') }}</th>
                                    <th style="min-width: 100px;">{{ __('Place of purchase') }}</th>
                                    <th style="min-width: 100px;">{{ __('Date') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($productRegistrations as $productRegistration)
                                    <tr>
                                        <td class="">#{{ $productRegistration['folio'] }}</td>
                                        <td class="">{{ $productRegistration['sku'] }}</td>
                                        <td class="">{{ $productRegistration['serialNumber'] }}</td>
                                        <td class="">{{ $productRegistration['productType'] }}</td>
                                        <td class="">{{ $productRegistration['placePurchase'] }}</td>
                                        <td class="">{{ $productRegistration['date'] }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End of PageContent -->
