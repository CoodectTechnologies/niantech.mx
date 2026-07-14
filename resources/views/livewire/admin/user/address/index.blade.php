<div>
    <div x-data="app" class="row g-5 g-xl-9">
        @include('admin.user.address.create')
        @foreach($addresses as $address)
            <div wire:key='address.{{ $address->id }}' class="col-lg-6 col-12">
                <!--begin::Card-->
                <div class="card card-flush h-md-100">
                    <!--begin::Card header-->
                    <div class="card-header ribbon ribbon-top ribbon-vertical">
                        <!--begin::Card title-->
                        <div class="card-title">
                            <h2>{{ $address->street }}</h2>
                        </div>
                        <!--end::Card title-->
                        @if($address->is_default)
                            <div class="ribbon-label bg-success">
                                <i class="fa-regular fa-truck-fast fs-2 text-white"></i>
                            </div>
                        @endif
                        @if($address->is_billing_default)
                            <div class="ribbon-label bg-info" @if($address->is_default) style="margin-right: 50px" @endif>
                                <i class="fa-regular fa-credit-card fs-2 text-white"></i>
                            </div>
                        @endif
                    </div>
                    <!--end::Card header-->
                    <!--begin::Card body-->
                    <div class="card-body pt-1">
                        <!--begin::shipping orders count-->
                        <div class="fw-bolder text-gray-600 mb-5">{{ __('Total orders with this address') }}:
                            {{ count($address->orders) }}
                        </div>
                        <span class="badge badge-success">{{ __('Shipping address') }}</span>
                        @if($address->is_billing)
                        <span class="badge badge-info">{{ __('Billing address') }}</span>
                        @endif
                        <!--end::shipping orders count-->
                        <!--begin::Info-->
                        <div class="d-flex flex-column text-gray-600">
                            @if(config('services.odoo.status'))
                                <div class="d-flex align-items-center py-2">
                                    <span class="bullet bg-dark me-3"></span>Proveedor: {{ $address->provider }}
                                </div>
                                <div class="d-flex align-items-center py-2">
                                    <span class="bullet bg-dark me-3"></span>Proveedor id:
                                    {{ $address->provider_id }}
                                </div>
                            @endif
                            <div class="d-flex align-items-center py-2">
                                <span class="bullet bg-dark me-3"></span>{{ $address->state?->country?->name ?? '' }}
                            </div>
                            <div class="d-flex align-items-center py-2">
                                <span class="bullet bg-dark me-3"></span>{{ $address->state?->name ?? '' }}
                            </div>
                            <div class="d-flex align-items-center py-2">
                                <span class="bullet bg-dark me-3"></span>{{ $address->municipality }}
                            </div>
                            <div class="d-flex align-items-center py-2">
                                <span class="bullet bg-dark me-3"></span>{{ $address->colony }}
                            </div>
                            <div class="d-flex align-items-center py-2">
                                <span class="bullet bg-dark me-3"></span>{{ $address->zip_code }}
                            </div>
                        </div>
                        <!--end::Info-->
                    </div>
                    <!--end::Card body-->
                    <!--begin::Card footer-->
                    <div class="card-footer flex-wrap pt-0">
                        @include('admin.user.address.edit')
                        @include('admin.user.address.delete')
                    </div>
                    <!--end::Card footer-->
                </div>
                <!--end::Card-->
            </div>
        @endforeach
    </div>
</div>

@script
    <script>
        Alpine.data('app', () => ({
            init(){
                Livewire.on('render', function() {
                    $('.modal').modal('hide');
                });
            },
            confirmDestroyAddress(id) {
                console.log('delete');
                swal.fire({
                    title: "{{ __('Are you sure?') }}",
                    text: "{{ __('You will not be able to retrieve this record') }}",
                    icon: "warning",
                    buttonsStyling: false,
                    showCancelButton: true,
                    confirmButtonText: "<i class='fa fa-trash'></i> <span class='font-weight-bold'>{{ __('Yes, delete') }}</span>",
                    cancelButtonText: "<i class='fas fa-arrow-circle-left'></i>  <span class='text-dark font-weight-bold'>{{ __('No, cancel') }}</span>",
                    reverseButtons: true,
                    cancelButtonClass: "btn btn-light-secondary font-weight-bold",
                    confirmButtonClass: "btn btn-danger",
                }).then(function(result) {
                    if (result.isConfirmed) {
                        @this.call('destroy', id);
                    }
                });
            }
        }));
    </script>
@endscript