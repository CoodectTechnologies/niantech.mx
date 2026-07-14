<div>
    <div x-data="app">
        <div class="card card-flush">
            <div class="card-header mt-6">
                <div class="card-title">
                    <div class="d-flex align-items-center position-relative my-1">
                        <span wire:loading.remove wire:target="filterSearch" class="svg-icon svg-icon-1 position-absolute ms-6">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                <rect opacity="0.5" x="17.0365" y="15.1223" width="8.15546" height="2" rx="1" transform="rotate(45 17.0365 15.1223)" fill="gray" />
                                <path d="M11 19C6.55556 19 3 15.4444 3 11C3 6.55556 6.55556 3 11 3C15.4444 3 19 6.55556 19 11C19 15.4444 15.4444 19 11 19ZM11 5C7.53333 5 5 7.53333 5 11C5 14.4667 7.53333 17 11 17C14.4667 17 17 14.4667 17 11C17 7.53333 14.4667 5 11 5Z" fill="gray" />
                            </svg>
                        </span>
                        <div wire:loading wire:target="filterSearch" class="spinner-border spinner-border-sm text-primary fs-6 position-absolute ms-6" role="status"></div>
                        <input wire:model.live.debounce.500ms="filterSearch" type="search" class="form-control form-control-solid w-250px ps-14" placeholder="{{ __('Buscar...') }}" />
                    </div>
                </div>
                <div class="card-toolbar m-0">
                    
                </div>
            </div>
            <div class="card-body">
                <div class="row g-6 mb-6 g-xl-9 mb-xl-9">
                    @foreach($subscriptions as $subscription)
                        <div wire:key="subscription.{{ $subscription->id }}" class="col-md-6 col-xxl-4">
                            <div class="card">
                                <div class="card-body d-flex flex-center flex-column py-9 px-5">
                                    <div class="symbol symbol-65px symbol-circle mb-5">
                                        <img src="{{ $subscription->user->imagePreview() }}" alt="{{ $subscription->user->name }}">
                                        {{-- <div class="bg-success position-absolute rounded-circle translate-middle start-100 top-100 border border-4 border-body h-15px w-15px ms-n3 mt-n3"></div> --}}
                                    </div>
                                    <a href="#" class="fs-4 text-gray-800 text-hover-primary fw-bold mb-0">{{ $subscription->plan->title }}</a>
                                    <div class="fw-semibold text-gray-500 mb-6">{{ $subscription->user->name }}</div>
                                    <div class="d-flex flex-center flex-wrap mb-5">
                                        <div class="border border-dashed rounded py-3 px-4 mx-2 mb-3">
                                            <div class="fs-6 fw-bold text-gray-700">{{ $subscription->created_at->toFormattedDateString() }}</div>
                                            <div class="fw-semibold text-gray-500">{{ __('Creación') }}</div>
                                        </div>
                                        <div class="border border-dashed rounded py-3 px-4 mx-2 mb-3">
                                            <div class="fs-6 fw-bold text-gray-700">{{ $subscription->updated_at->toFormattedDateString() }}</div>
                                            <div class="fw-semibold text-gray-500">{{ __('Última actualización') }}</div>
                                        </div>
                                    </div>
                                    {!! $subscription->stripe_status !!}
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

@script
    <script>
        Alpine.data('app', () => ({
            init(){
                $('.filter-statuses').select2().on('change', function (e) {
                    let data = $(this).select2("val");
                    @this.set('filterStatus', data);
                });
            },
        }));
    </script>
@endscript
