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
                            <h4 class="icon-box-title text-capitalize ls-normal mb-0">{{ __('RMA') }}</h4>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="shop-table account-rma-table mb-6">
                            <thead>
                                <tr>
                                    <th style="min-width: 100px;">{{ __('Folio') }}</th>
                                    <th style="min-width: 100px;">{{ __('SKU') }}</th>
                                    <th style="min-width: 100px;">{{ __('Serial number') }}</th>
                                    <th style="min-width: 100px;">{{ __('Date') }}</th>
                                    <th style="min-width: 100px;">{{ __('Observation') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($rmas as $rma)
                                    <tr>
                                        <td class="">#{{ $rma['folio'] }}</td>
                                        <td class="">{{ $rma['sku'] }}</td>
                                        <td class="">{{ $rma['serialNumber'] }}</td>
                                        <td class="">{{ $rma['date'] }}</td>
                                        <td class="">{{ $rma['observation'] }}</td>
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
