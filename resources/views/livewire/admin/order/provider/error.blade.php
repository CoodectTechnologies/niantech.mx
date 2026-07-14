<div>
    @if(count($orderProviderErrors))
        <div class="card card-flush my-4 flex-row-fluid">
            <div class="card-header">
                <div class="card-title">
                    <h3>{{ __('There are errors when trying to send the order to the supplier') }}</h3>
                </div>
                <div class="card-toolbar">
                    <!--begin::Button-->
                    <button wire:click.prevent="resendOrdersProvider" type="button" class="btn btn-sm btn-light-primary">
                        <!--begin::Svg Icon | path: assets/media/icons/duotune/abstract/abs013.svg-->
                        <span class="svg-icon svg-icon-2 me-3"><svg xmlns="http://www.w3.org/2000/svg" width="24"
                                height="24" viewBox="0 0 24 24" fill="none">
                                <path opacity="0.3"
                                    d="M13.341 22H11.341C10.741 22 10.341 21.6 10.341 21V18C10.341 17.4 10.741 17 11.341 17H13.341C13.941 17 14.341 17.4 14.341 18V21C14.341 21.6 13.941 22 13.341 22ZM18.5409 10.7L21.141 9.19997C21.641 8.89997 21.7409 8.29997 21.5409 7.79997L20.5409 6.09997C20.2409 5.59997 19.641 5.49997 19.141 5.69997L16.5409 7.19997C16.0409 7.49997 15.941 8.09997 16.141 8.59997L17.141 10.3C17.441 10.8 18.0409 11 18.5409 10.7ZM8.14096 7.29997L5.54095 5.79997C5.04095 5.49997 4.44096 5.69997 4.14096 6.19997L3.14096 7.89997C2.84096 8.39997 3.04095 8.99997 3.54095 9.29997L6.14096 10.8C6.64096 11.1 7.24095 10.9 7.54095 10.4L8.54095 8.69997C8.74095 8.19997 8.64096 7.49997 8.14096 7.29997Z"
                                    fill="gray" />
                                <path
                                    d="M13.3409 7H11.3409C10.7409 7 10.3409 6.6 10.3409 6V3C10.3409 2.4 10.7409 2 11.3409 2H13.3409C13.9409 2 14.3409 2.4 14.3409 3V6C14.3409 6.6 13.9409 7 13.3409 7ZM5.54094 18.2L8.14095 16.7C8.64095 16.4 8.74094 15.8 8.54094 15.3L7.54094 13.6C7.24094 13.1 6.64095 13 6.14095 13.2L3.54094 14.7C3.04094 15 2.94095 15.6 3.14095 16.1L4.14095 17.8C4.44095 18.3 5.04094 18.5 5.54094 18.2ZM21.1409 14.8L18.5409 13.3C18.0409 13 17.4409 13.2 17.1409 13.7L16.1409 15.4C15.8409 15.9 16.0409 16.5 16.5409 16.8L19.1409 18.3C19.6409 18.6 20.2409 18.4 20.5409 17.9L21.5409 16.2C21.7409 15.7 21.6409 15 21.1409 14.8Z"
                                    fill="gray" />
                            </svg>
                        </span>
                        <!--end::Svg Icon-->
                        {{ __('Resend') }}
                        <span wire:loading wire:target="resendOrdersProvider"
                            class="spinner-border spinner-border-sm align-middle ms-2"></span>
                    </button>
                    <!--end::Button-->
                </div>
            </div>
        </div>
    @endif
    @foreach($orderProviderErrors as $orderProviderError)
        <div class="card card-flush my-4 flex-row-fluid" style="background-color: #f1416c2e;">
            <div class="card-header">
                <div class="card-title">
                    <h3>{{ __('Error in the warehouse') }}: {{ $orderProviderError->productWarehouse->name }}</h3>
                    <p><span class="badge badge-secondary">Número de reintentos:
                            {{ $orderProviderError->retry_limit }}</span></p>
                </div>
            </div>
            <div class="card-body pt-0">
                <div class="table-responsive">
                    <table class="table align-middle table-row-dashed fs-6 gy-5">
                        <!--begin::Table head-->
                        <thead>
                            <!--begin::Table row-->
                            <tr class="text-start text-muted fw-bolder fs-7 text-uppercase gs-0">
                                <th class="">{{ __('Provider') }}</th>
                                <th class="">{{ __('Error') }}</th>
                            </tr>
                            <!--end::Table row-->
                        </thead>
                        <!--end::Table head-->
                        <!--begin::Table body-->
                        <tbody class="text-gray-600 fw-bold">
                            <!--begin::Table row-->
                            <tr>
                                <td>
                                    <span class="badge badge-primary">{{ $orderProviderError->provider }}
                                        {{ $orderProviderError->provider_id }}</span>
                                </td>
                                <td>
                                    <pre>
                                        @json(json_decode($orderProviderError->error), JSON_PRETTY_PRINT)
                                    </pre>
                                </td>
                            </tr>
                            <!--end::Table row-->
                        </tbody>
                        <!--end::Table body-->
                    </table>
                </div>
            </div>
        </div>
    @endforeach
</div>
