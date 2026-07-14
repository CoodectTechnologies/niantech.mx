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
                <div class="d-flex flex-wrap flex-stack">
                    <div class="d-flex flex-wrap my-2">
                        @include('admin.subscription.plan.create')
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body pt-0" >
            <div class="table-responsive">
                <table class="table align-middle table-row-dashed fs-6 gy-5 mb-0">
                    <thead>
                        <tr class="text-start text-gray-400 fw-bolder fs-7 text-uppercase gs-0">
                            <th class="min-w-125px">{{ __('Titulo') }}</th>
                            <th class="min-w-125px">{{ __('Subtitulo') }}</th>
                            <th class="min-w-125px">{{ __('Monto por mes') }}</th>
                            <th class="min-w-125px">{{ __('Monto por año') }}</th>
                            <th class="min-w-125px">{{ __('Días gratis') }}</th>
                            <th class="min-w-125px">{{ __('Destacado') }}</th>
                            <th class="min-w-125px">{{ __('Estado') }}</th>
                            <th class="min-w-100px">{{ __('Acciones') }}</th>
                        </tr>
                    </thead>
                    <tbody class="fw-bold text-gray-600">
                        @foreach($plans as $plan)
                            <tr wire:key="plan.{{ $plan->id }}">
                                <td>{{ $plan->title }}</td>
                                <td>{{ $plan->subtitle }}</td>
                                <td>{{ number_format($plan->amount_month, 2) }}</td>
                                <td>{{ number_format($plan->amount_year, 2) }}</td>
                                <td>{{ $plan->free_trial_days }}</td>
                                <td>{{ $plan->featured ? 'HOT' : '' }}</td>
                                <td>{!! $plan->statusToString() !!}</td>
                                <td>
                                    @include('admin.subscription.plan.edit')
                                    @include('admin.subscription.plan.delete')
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{ $plans->links() }}
        </div>
    </div>
</div>

@script
    <script>
        Alpine.data('app', () => ({
            init(){
                Livewire.on('render', function(){
                    $('.modal').modal('hide');
                });
            },
        }));
    </script>
@endscript
