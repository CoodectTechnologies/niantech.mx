<div class="card">
    <!--begin::Card header-->
    <div class="card-header border-0 pt-6">
        <!--begin::Card toolbar-->
        <div class="card-toolbar d-flex justify-content-end w-100">
            <!--begin::Button-->
            <a href="{{ route('admin.chatbot.edit', $chatbot) }}" class="btn btn-light-success">
                <i class="fa-light fa-pen-to-square"></i>
                {{ __('Edit') }}
            </a>
            <!--end::Button-->
        </div>
        <!--end::Card toolbar-->
    </div>
    <!--end::Card header-->
    <!--begin::Card body-->
    <div class="card-body">
        <!--begin::Details-->
        <div class="d-flex flex-column gap-7">
            <!--begin::Row-->
            <div class="row">
                <div class="col-md-12 mb-4">
                    <img src="{{ $chatbot->imagePreview() }}" alt="Chatbot Image" class="img-thumbnail" style="max-width: 180px;">
                </div>
            </div>
            <!--end::Row-->
            <!--begin::Row-->
            <div class="row">
                <div class="col-md-6">
                    <div class="fw-bold text-gray-600 mb-2">ID</div>
                    <div class="fw-bolder fs-6 text-gray-800">{{ $chatbot->id }}</div>
                </div>
                <div class="col-md-6">
                    <div class="fw-bold text-gray-600 mb-2">{{ __('Name') }}</div>
                    <div class="fw-bolder fs-6 text-gray-800">{{ $chatbot->name }}</div>
                </div>
            </div>
            <!--end::Row-->
            <!--begin::Row-->
            <div class="row">
                <div class="col-md-6">
                    <div class="fw-bold text-gray-600 mb-2">{{ __('Model') }}</div>
                    <div class="fw-bolder fs-6 text-gray-800">
                        <span class="badge badge-light-primary fs-7">{{ $chatbot->model }}</span>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="fw-bold text-gray-600 mb-2">{{ __('Temperature') }}</div>
                    <div class="fw-bolder fs-6 text-gray-800">{{ $chatbot->temperature }}</div>
                </div>
            </div>
            <!--end::Row-->
            <!--begin::Row--> 
            <div class="row">
                <div class="col-md-6">
                    <div class="fw-bold text-gray-600 mb-2">{{ __('Status') }}</div>
                    <div class="fw-bolder fs-6 text-gray-800">
                        @if($chatbot->status)
                            <span class="badge bg-success">Activo</span>
                        @else
                            <span class="badge bg-danger">Inactivo</span>
                        @endif
                    </div>
                </div>
            </div>
            <!--end::Row-->
            <!--begin::System Prompt-->
            <div class="separator"></div>
            <div>
                <div class="fw-bold text-gray-600 mb-3">{{ __('System Prompt') }}</div>
                <div class="p-5 bg-light-primary rounded border border-primary border-dashed">
                    <div class="text-gray-800 fw-normal" style="white-space: pre-line;">
                        {{ $chatbot->system_promt }}</div>
                </div>
            </div>
            <!--end::System Prompt-->
            <!--begin::Dates-->
            <div class="separator"></div>
            <div class="row">
                <div class="col-md-6">
                    <div class="fw-bold text-gray-600 mb-2">{{ __('Created at') }}</div>
                    <div class="fw-bolder fs-6 text-gray-800">
                        <i class="fa-light fa-calendar"></i> {{ $chatbot->created_at->format('d/m/Y H:i:s') }}
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="fw-bold text-gray-600 mb-2">{{ __('Updated at') }}</div>
                    <div class="fw-bolder fs-6 text-gray-800">
                        <i class="fa-light fa-calendar"></i> {{ $chatbot->updated_at->format('d/m/Y H:i:s') }}
                    </div>
                </div>
            </div>
            <!--end::Dates-->
            @if($chatbot->user)
                <div class="separator"></div>
                <!--begin::User-->
                <div>
                    <div class="fw-bold text-gray-600 mb-2">{{ __('Created by') }}</div>
                    <div class="d-flex align-items-center">
                        <div class="symbol symbol-35px me-3">
                            <div class="symbol-label bg-light-primary">
                                <i class="fa-light fa-user fs-3 text-primary"></i>
                            </div>
                        </div>
                        <div class="d-flex flex-column">
                            <div class="fw-bolder text-gray-800">{{ $chatbot->user->name }}</div>
                            <div class="text-gray-600 fs-7">{{ $chatbot->user->email }}</div>
                        </div>
                    </div>
                </div>
                <!--end::User-->
            @endif
        </div>
        <!--end::Details-->
    </div>
</div>