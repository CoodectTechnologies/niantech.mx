<div>
    <!--begin::Details-->
    <div class="card mb-5 mb-xl-10">
        <!--begin::Card header-->
        <div class="card-header">
            <!--begin::Card title-->
            <div class="card-title m-0">
                <h3 class="fw-bolder m-0">{{ __('Questionnaire Details') }}</h3>
            </div>
            <!--end::Card title-->
        </div>
        <!--begin::Card header-->
        <!--begin::Card body-->
        <div class="card-body p-9">
            <!--begin::Row-->
            <div class="row mb-7">
                <!--begin::Label-->
                <label class="col-lg-4 fw-bold text-muted">{{ __('Name') }}</label>
                <!--end::Label-->
                <!--begin::Col-->
                <div class="col-lg-8">
                    <span class="fw-bolder fs-6 text-gray-800">{{ $questionnaire->name }}</span>
                </div>
                <!--end::Col-->
            </div>
            <!--end::Row-->
            <!--begin::Row-->
            <div class="row mb-7">
                <!--begin::Label-->
                <label class="col-lg-4 fw-bold text-muted">{{ __('Description') }}</label>
                <!--end::Label-->
                <!--begin::Col-->
                <div class="col-lg-8">
                    <span class="fw-bold fs-6 text-gray-800">{{ $questionnaire->description }}</span>
                </div>
                <!--end::Col-->
            </div>
            <!--end::Row-->
            <!--begin::Row-->
            <div class="row mb-7">
                <!--begin::Label-->
                <label class="col-lg-4 fw-bold text-muted">{{ __('Status') }}</label>
                <!--end::Label-->
                <!--begin::Col-->
                <div class="col-lg-8">
                    <span class="badge badge-light-{{ $questionnaire->status === 'Publicado' ? 'success' : 'warning' }}">
                        {{ $questionnaire->status }}
                    </span>
                </div>
                <!--end::Col-->
            </div>
            <!--end::Row-->
            <!--begin::Row-->
            <div class="row mb-7">
                <!--begin::Label-->
                <label class="col-lg-4 fw-bold text-muted">{{ __('Minimum percentage for aptitude') }}</label>
                <!--end::Label-->
                <!--begin::Col-->
                <div class="col-lg-8">
                    <span class="fw-bolder fs-6 text-gray-800">{{ $questionnaire->min_positive_percentage }}%</span>
                </div>
                <!--end::Col-->
            </div>
            <!--end::Row-->
            <!--begin::Row-->
            <div class="row mb-7">
                <!--begin::Label-->
                <label class="col-lg-4 fw-bold text-muted">{{ __('Total Questions') }}</label>
                <!--end::Label-->
                <!--begin::Col-->
                <div class="col-lg-8">
                    <span class="fw-bolder fs-6 text-gray-800">{{ $questionnaire->questions()->count() }}</span>
                </div>
                <!--end::Col-->
            </div>
            <!--end::Row-->
            <!--begin::Row-->
            <div class="row mb-7">
                <!--begin::Label-->
                <label class="col-lg-4 fw-bold text-muted">{{ __('Total Responses') }}</label>
                <!--end::Label-->
                <!--begin::Col-->
                <div class="col-lg-8">
                    <span class="fw-bolder fs-6 text-gray-800">{{ $questionnaire->responses()->count() }}</span>
                </div>
                <!--end::Col-->
            </div>
            <!--end::Row-->
        </div>
        <!--end::Card body-->
    </div>
    <!--end::Details-->

    <!--begin::Questions-->
    <div class="card mb-5 mb-xl-10">
        <!--begin::Card header-->
        <div class="card-header">
            <!--begin::Card title-->
            <div class="card-title m-0">
                <h3 class="fw-bolder m-0">{{ __('Questions') }}</h3>
            </div>
            <!--end::Card title-->
        </div>
        <!--begin::Card header-->
        <!--begin::Card body-->
        <div class="card-body p-9">
            @if($questionnaire->questions()->count() > 0)
                <div class="list-group">
                    @foreach($questionnaire->questions()->with('options')->orderBy('order')->get() as $question)
                        <div class="list-group-item mb-4">
                            <div class="d-flex justify-content-between align-items-start">
                                <div class="flex-grow-1">
                                    <h6 class="mb-2">{{ $loop->iteration }}. {{ $question->question }}</h6>
                                    <span class="badge badge-light-{{ $question->type === 'single' ? 'primary' : 'info' }} mb-2">
                                        {{ $question->type === 'single' ? __('Single selection') : __('Multiple selection') }}
                                    </span>
                                    <div class="mt-2">
                                        @foreach($question->options()->orderBy('order')->get() as $option)
                                            <div class="form-check mb-1">
                                                @if($question->type === 'single')
                                                    <input type="radio" class="form-check-input" disabled>
                                                @else
                                                    <input type="checkbox" class="form-check-input" disabled>
                                                @endif
                                                <label class="form-check-label">
                                                    {{ $option->option_text }}
                                                    @if($option->is_positive)
                                                        <span class="badge badge-success badge-sm">✓ {{ __('Positive') }}</span>
                                                    @endif
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="alert alert-info">{{ __('No questions added yet.') }}</div>
            @endif
        </div>
        <!--end::Card body-->
    </div>
    <!--end::Questions-->

    <!--begin::Responses-->
    <div class="card">
        <!--begin::Card header-->
        <div class="card-header border-0 pt-6">
            <!--begin::Card title-->
            <div class="card-title">
                <!--begin::Search-->
                <div class="d-flex align-items-center position-relative my-1">
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
                        class="form-control form-control-solid w-250px ps-14" 
                        placeholder="{{ __('Search by name, email or phone...') }}" />
                </div>
                <!--end::Search-->
            </div>
            <!--end::Card title-->
            <!--begin::Card toolbar-->
            <div class="card-toolbar">
                <!--begin::Filter-->
                <select wire:model.live="filterApt" class="form-select form-select-solid w-150px">
                    <option value="all">{{ __('All') }}</option>
                    <option value="apt">{{ __('Apt') }}</option>
                    <option value="not-apt">{{ __('Not Apt') }}</option>
                </select>
                <!--end::Filter-->
            </div>
            <!--end::Card toolbar-->
        </div>
        <!--end::Card header-->
        <!--begin::Card body-->
        <div class="card-body py-4">
            <!--begin::Table-->
            <div class="table-responsive">
                <table class="table align-middle table-row-dashed fs-6 gy-5">
                    <!--begin::Table head-->
                    <thead>
                        <tr class="text-start text-muted fw-bolder fs-7 text-uppercase gs-0">
                            <th class="min-w-125px">{{ __('Name') }}</th>
                            <th class="min-w-125px">{{ __('Email') }}</th>
                            <th class="min-w-125px">{{ __('Phone') }}</th>
                            <th class="min-w-100px">{{ __('Positive %') }}</th>
                            <th class="min-w-100px">{{ __('Status') }}</th>
                            <th class="min-w-100px">{{ __('Date') }}</th>
                            <th class="text-end min-w-100px">{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <!--end::Table head-->
                    <!--begin::Table body-->
                    <tbody class="text-gray-600 fw-bold">
                        @forelse ($responses as $response)
                            <tr>
                                <td>{{ $response->name }}</td>
                                <td>{{ $response->email }}</td>
                                <td>{{ $response->phone ?? '-' }}</td>
                                <td>
                                    <span class="badge badge-light-primary">{{ number_format($response->positive_percentage, 1) }}%</span>
                                </td>
                                <td>
                                    @if($response->is_apt)
                                        <span class="badge badge-light-success">{{ __('Apt') }}</span>
                                    @else
                                        <span class="badge badge-light-danger">{{ __('Not Apt') }}</span>
                                    @endif
                                </td>
                                <td>{{ $response->created_at->format('Y-m-d H:i') }}</td>
                                <td class="text-end">
                                    <button type="button" class="btn btn-sm btn-light-primary" 
                                        data-bs-toggle="modal" data-bs-target="#responseModal{{ $response->id }}">
                                        {{ __('View') }}
                                    </button>
                                    <button wire:click="deleteResponse({{ $response->id }})" 
                                        wire:confirm="{{ __('Are you sure you want to delete this response?') }}"
                                        class="btn btn-sm btn-light-danger">
                                        {{ __('Delete') }}
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">{{ __('No responses found.') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                    <!--end::Table body-->
                </table>
            </div>
            <!--end::Table-->
            
            <!-- Modals -->
            @foreach($responses as $response)
                <div class="modal fade" id="responseModal{{ $response->id }}" tabindex="-1" aria-hidden="true" wire:ignore.self>
                    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">{{ __('Response Details') }} - {{ $response->name }}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="mb-5 p-4 bg-light rounded">
                                    <div class="row">
                                        <div class="col-md-6 mb-2">
                                            <strong>{{ __('Email') }}:</strong> {{ $response->email }}
                                        </div>
                                        <div class="col-md-6 mb-2">
                                            <strong>{{ __('Phone') }}:</strong> {{ $response->phone ?? '-' }}
                                        </div>
                                        <div class="col-md-6 mb-2">
                                            <strong>{{ __('Positive Percentage') }}:</strong> 
                                            <span class="badge badge-primary">{{ number_format($response->positive_percentage, 1) }}%</span>
                                        </div>
                                        <div class="col-md-6 mb-2">
                                            <strong>{{ __('Status') }}:</strong> 
                                            @if($response->is_apt)
                                                <span class="badge badge-success">{{ __('Apt') }}</span>
                                            @else
                                                <span class="badge badge-danger">{{ __('Not Apt') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                
                                <h6 class="mb-3 fw-bolder">{{ __('Answers') }}:</h6>
                                <div class="table-responsive">
                                    <table class="table table-row-bordered table-striped align-middle">
                                        <thead>
                                            <tr class="fw-bold text-muted bg-light">
                                                <th class="ps-4" style="width: 50%;">{{ __('Question') }}</th>
                                                <th style="width: 50%;">{{ __('Answer') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($response->answers as $answer)
                                                <tr>
                                                    <td class="ps-4">
                                                        <div class="text-gray-800 fw-normal">{{ $answer->question->question }}</div>
                                                    </td>
                                                    <td>
                                                        <span class="badge badge-light-{{ $answer->option->is_positive ? 'success' : 'secondary' }}">
                                                            {{ $answer->option->option_text }}
                                                            @if($answer->option->is_positive)
                                                                <i class="fas fa-check ms-1"></i>
                                                            @endif
                                                        </span>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Close') }}</button>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
            <!-- End Modals -->
            <div class="mt-5">
                {{ $responses->links() }}
            </div>
        </div>
        <!--end::Card body-->
    </div>
    <!--end::Responses-->
</div>
