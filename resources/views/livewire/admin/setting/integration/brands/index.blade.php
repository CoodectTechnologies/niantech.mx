<div>
    <div class="card card-access-payments shadow bg-body rounded">
        <div class="card-header align-items-center border-0 mt-4">
            <h3 class="card-title align-items-start flex-column">
                <img width="200" src="{{ asset('assets/admin/media/provider/brands.png') }}" alt="Odoo">
            </h3>
            <div class="card-toolbar">
                @include('admin.setting.integration.brands.edit')
            </div>
        </div>
        <div class="card-body">
            <div class="mb-5">
                <p class="fw-bolder text-dark mb-0">{{ __('Status') }}</p>
                <div class="fw-bold text-gray-700">
                    @if(config('services.vadeto_brands.status'))
                        <span class="badge badge-primary">{{ __('Active') }}</span>
                    @else
                        <span class="badge badge-secondary">{{ __('Off') }}</span>
                    @endif
                </div>
            </div>
            <div class="mb-5">
                <p class="fw-bolder text-dark mb-0">API URL</p>
                <div class="fw-bold text-gray-700">
                    {{ config('services.vadeto_brands.url') }}
                </div>
            </div>
            <div class="mb-5">
                <p class="fw-bolder text-dark mb-0">Marcas permitidas</p>
                <div class="fw-bold text-gray-700">
                    @foreach(config('services.vadeto_brands.allowed') as $brand)
                        <span class="badge badge-primary">{{ $brand }}</span>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
