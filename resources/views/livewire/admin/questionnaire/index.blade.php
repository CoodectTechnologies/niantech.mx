<div>
    <!--begin::Card-->
    <div class="card">
        <!--begin::Card header-->
        <div class="card-header border-0 pt-6">
            <!--begin::Card title-->
            <div class="card-title">
                <!--begin::Search-->
                <div class="d-flex align-items-center position-relative my-1">
                    <!--begin::Svg Icon-->
                    <span class="svg-icon svg-icon-1 position-absolute ms-6">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none">
                            <rect opacity="0.5" x="17.0365" y="15.1223" width="8.15546" height="2" rx="1"
                                transform="rotate(45 17.0365 15.1223)" fill="gray" />
                            <path
                                d="M11 19C6.55556 19 3 15.4444 3 11C3 6.55556 6.55556 3 11 3C15.4444 3 19 6.55556 19 11C19 15.4444 15.4444 19 11 19ZM11 5C7.53333 5 5 7.53333 5 11C5 14.4667 7.53333 17 11 17C14.4667 17 17 14.4667 17 11C17 7.53333 14.4667 5 11 5Z"
                                fill="gray" />
                        </svg>
                    </span>
                    <input wire:model.live.debounce.500ms="search" type="search"
                        class="form-control form-control-solid w-250px ps-14" placeholder="{{ __('Search...') }}" />
                </div>
                <!--end::Search-->
            </div>
            <!--begin::Card title-->
            <!--begin::Card toolbar-->
            <div class="card-toolbar">
                <!--begin::Button-->
                <a href="{{ route('admin.questionnaire.create') }}" class="btn btn-light-primary">
                    <i class="fa-light fa-plus"></i>
                    {{ __('New') }}
                </a>
                <!--end::Button-->
            </div>
            <!--end::Card toolbar-->
        </div>
        <!--end::Card header-->
        <!--begin::Card body-->
        <div class="card-body py-4">
            <div class="row g-10">
                @foreach($questionnaires as $questionnaire)
                    <!--begin::Col-->
                    <div class="col-md-4">
                        <!--begin::Card-->
                        <div class="card-xl-stretch me-md-6">
                            <!--begin::Overlay-->
                            <a class="d-block overlay" href="{{ route('admin.questionnaire.show', $questionnaire) }}">
                                <!--begin::Image-->
                                <div class="overlay-wrapper bgi-no-repeat bgi-position-center bgi-size-cover card-rounded min-h-175px"
                                    style="background-image:url('{{ $questionnaire->imagePreview() }}')"></div>
                                <!--end::Image-->
                                <!--begin::Action-->
                                <div class="overlay-layer card-rounded bg-dark bg-opacity-25">
                                    <i class="bi bi-eye-fill fs-2x text-white"></i>
                                </div>
                                <!--end::Action-->
                            </a>
                            <!--end::Overlay-->
                            <!--begin::Body-->
                            <div class="mt-5">
                                <!--begin::Title-->
                                <a href="{{ route('admin.questionnaire.show', $questionnaire) }}"
                                    class="fs-4 text-dark fw-bolder text-hover-primary text-dark lh-base">{{ $questionnaire->name }}</a>
                                <!--end::Title-->
                                <!--begin::Text-->
                                <div class="fw-bold fs-5 text-gray-600 text-dark mt-3">{{ Str::limit($questionnaire->description, 100) }}</div>
                                <!--end::Text-->
                                <!--begin::Label-->
                                <span class="badge badge-light-{{ $questionnaire->status === 'Publicado' ? 'success' : 'warning' }} fw-bolder my-2">
                                    {{ $questionnaire->status }}
                                </span>
                                <!--end::Label-->
                                <!--begin::Stats-->
                                <div class="fs-6 fw-bolder mt-5 mb-5 d-flex flex-stack">
                                    <span class="badge border border-dashed fs-2 fw-bolder text-dark p-2">
                                        <span class="fs-6 fw-bold text-gray-400">
                                            <i class="fa fa-list me-2"></i>
                                        </span> {{ $questionnaire->questions()->count() }} {{ __('Questions') }}
                                        <span class="fs-6 ms-4 fw-bold text-gray-400">
                                            <i class="fa fa-users me-2"></i>
                                        </span> {{ $questionnaire->responses()->count() }} {{ __('Responses') }}
                                    </span>
                                </div>
                                <!--end::Stats-->
                            </div>
                            <!--end::Body-->
                            <!--begin::Footer-->
                            <div class="d-flex flex-stack flex-wrap">
                                <!--begin::Item-->
                                <div class="d-flex align-items-center pe-2">
                                    @if($questionnaire->user)
                                        <!--begin::Avatar-->
                                        <div class="symbol symbol-35px symbol-circle me-3">
                                            <img alt="{{ $questionnaire->user->name }}"
                                                src="{{ $questionnaire->user->imagePreview() }}" />
                                        </div>
                                        <!--end::Avatar-->
                                        <!--begin::Text-->
                                        <div class="fs-5 fw-bolder">
                                            <span class="text-gray-700">{{ $questionnaire->user->name }}</span>
                                        </div>
                                        <!--end::Text-->
                                    @endif
                                    <span class="text-muted ms-4">{{ $questionnaire->created_at->diffForHumans() }}</span>
                                </div>
                                <!--end::Item-->
                            </div>
                            <!--end::Footer-->
                            <div class="text-center mt-4">
                                <a href="{{ route('admin.questionnaire.edit', $questionnaire) }}"
                                    class="btn btn-light-success btn-sm">{{ __('Update') }}</a>
                                <button wire:click="delete({{ $questionnaire->id }})" 
                                    wire:confirm="{{ __('Are you sure you want to delete this record?') }}"
                                    class="btn btn-light-danger btn-sm"
                                    wire:loading.attr="disabled" wire:target="delete({{ $questionnaire->id }})">
                                    <span wire:loading.remove wire:target="delete({{ $questionnaire->id }})">{{ __('Delete') }}</span>
                                    <span wire:loading wire:target="delete({{ $questionnaire->id }})" class="spinner-border spinner-border-sm"></span>
                                </button>
                            </div>
                        </div>
                        <!--end::Card-->
                    </div>
                    <!--end::Col-->
                @endforeach
            </div>
            <div class="mt-5">
                {{ $questionnaires->links() }}
            </div>
        </div>
        <!--end::Card body-->
    </div>
    <!--end::Card-->
</div>
