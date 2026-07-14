<!-- Start of PageContent -->
<div class="page-content pt-2">
    <div class="container">
        <div class="tab tab-vertical row gutter-lg">

            @include('ecommerce.account.menu.index')

            <div class="tab-content mb-6">
                <div class="tab-pane active in" id="account-addresses">
                    <div class="d-flex justify-content-between align-items-center flex-wrap mb-4">
                        <div>
                            <h4 class="title title-underline ls-25 font-weight-bold mb-1">
                                {{ __('Addresses') }}
                            </h4>
                            <small class="text-muted">
                                {{ __('Manage your shipping and billing addresses') }}
                            </small>
                        </div>
                        <a href="{{ route('ecommerce.account.address.create') }}"
                            class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>
                            {{ __('New address') }}
                        </a>
                    </div>
                    <div class="ecommerce-address">
                        @include('ecommerce.components.alert')
                        @if($addresses->isEmpty())
                            <div class="card border-0 shadow-sm">
                                <div class="card-body text-center py-5">
                                    <h5>{{ __('No addresses found') }}</h5>
                                    <p class="text-muted mb-4">
                                        {{ __('Add your first shipping or billing address.') }}
                                    </p>
                                </div>
                            </div>
                        @else
                            <div class="row">
                                @foreach($addresses as $address)
                                    <div class="col-lg-6 mb-4">
                                        <div class="card h-100 shadow-sm">
                                            <div class="card-body p-4">
                                                <div class="d-flex justify-content-between align-items-start mb-3">
                                                    <div>
                                                        <h5 class="fw-bold mb-2"><i class="fas fa-map-marker-alt text-warning me-2"></i>{{ $address->name }}</h5>
                                                        @if($address->company)
                                                            <div class="text-muted mb-2">{{ $address->company }}</div>
                                                        @endif
                                                        <div class="fw-semibold">{{ $address->street }}</div>
                                                        <div>{{ $address->state?->name }}, CP {{ $address->zip_code }}</div>
                                                        <div class="text-uppercase text-muted">{{ $address->state?->country?->name ?? '' }}</div>
                                                    </div>
                                                    <div>
                                                        @if($address->is_default)
                                                            <span class="badge rounded-pill bg-success text-dark px-3 py-2">{{ __('Envío predeterminado') }}</span>
                                                        @endif
                                                        @if($address->is_billing_default)
                                                            <span class="badge rounded-pill bg-info px-3 py-2">{{ __('Facturación predeterminada') }}</span>
                                                        @endif
                                                    </div>
                                                </div>
                                                <hr>
                                                <div class="mb-3">
                                                    <div><i class="fas fa-phone text-muted me-3"></i>{{ $address->phone }}</div>
                                                </div>
                                                <div class="d-flex flex-wrap">
                                                    <div class="d-flex flex-wrap">
                                                        <a href="{{ route('ecommerce.account.address.edit', $address) }}"
                                                        class="btn btn-success btn-rounded btn-sm me-2 mb-2">
                                                            <i class="fas fa-edit me-1"></i>
                                                            {{ __('Edit') }}
                                                        </a>
                                                        @if(!($address?->orders_count ?? 0))
                                                           <a
                                                                wire:click="destroy({{ $address->id }})"
                                                                wire:confirm="{{ __('You will not be able to retrieve this record') }}"
                                                                href="#0"
                                                                class="btn btn-primary btn-outline btn-sm mb-2">
                                                                <i class="fas fa-trash-alt me-1"></i>
                                                                {{ __('Delete') }}
                                                            </a>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End of PageContent -->