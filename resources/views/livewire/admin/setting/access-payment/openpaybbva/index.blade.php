<div>
    <div class="card card-access-payments shadow bg-body rounded">
        <div class="card-header align-items-center border-0 mt-4">
            <h3 class="card-title align-items-start flex-column">
                <img width="120" src="{{ asset('assets/admin/media/method_payment/openpay-bbva.webp') }}"
                    alt="Openpay BBVA">
            </h3>
            <div class="card-toolbar">
                @include('admin.setting.access-payment.openpaybbva.edit')
            </div>
        </div>
        <div class="card-body">
            <div class="mb-5">
                <p class="fw-bolder text-dark mb-0">{{ __('Status') }}</p>
                <div class="fw-bold text-gray-700">
                    @if(config('services.openpay_bbva.status'))
                        <span class="badge badge-primary">{{ __('Active') }}</span>
                    @else
                        <span class="badge badge-secondary">{{ __('Off') }}</span>
                    @endif
                </div>
            </div>
            <div class="mb-5">
                <p class="fw-bolder text-dark mb-0">Public key</p>
                <div class="fw-bold text-gray-700">
                    {{ config('services.openpay_bbva.public') }}
                </div>
            </div>
            <div class="mb-5">
                <p class="fw-bolder text-dark mb-0">Private key</p>
                <div class="fw-bold text-gray-700">
                    {{ config('services.openpay_bbva.private') }}
                </div>
            </div>
        </div>
    </div>
</div>
