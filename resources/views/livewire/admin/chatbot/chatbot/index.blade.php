<div>
    <!--begin::Card-->
    <div class="card card-flush">
        <!--begin::Card header-->
        <div class="card-header border-0 pt-6">
            <!--begin::Card title-->
            <div class="card-title">
                <!--begin::Search-->
                <div class="d-flex align-items-center position-relative my-1">
                    <!--begin::Svg Icon | path: icons/duotune/general/gen021.svg-->
                    <span class="svg-icon svg-icon-1 position-absolute ms-6">
                        <span wire:loading.remove wire:target="search">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none">
                                <rect opacity="0.5" x="17.0365" y="15.1223" width="8.15546" height="2" rx="1"
                                    transform="rotate(45 17.0365 15.1223)" fill="gray" />
                                <path
                                    d="M11 19C6.55556 19 3 15.4444 3 11C3 6.55556 6.55556 3 11 3C15.4444 3 19 6.55556 19 11C19 15.4444 15.4444 19 11 19ZM11 5C7.53333 5 5 7.53333 5 11C5 14.4667 7.53333 17 11 17C14.4667 17 17 14.4667 17 11C17 7.53333 14.4667 5 11 5Z"
                                    fill="gray" />
                            </svg>
                        </span>
                        <span wire:loading wire:target="search" class="spinner-border spinner-border-sm text-primary"></span>
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
                <a href="{{ route('admin.chatbot.create') }}" class="btn btn-light-primary">
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
            <!--begin::Table-->
            <div class="table-responsive">
                <table class="table align-middle table-row-dashed fs-6 gy-5">
                    <!--begin::Table head-->
                    <thead>
                        <!--begin::Table row-->
                        <tr class="text-start text-muted fw-bolder fs-7 text-uppercase gs-0">
                            <th class="">{{ __('Name') }}</th>
                            <th class="">{{ __('Model') }}</th>
                            <th class="">{{ __('Temperature') }}</th>
                            <th class="">{{ __('Date') }}</th>
                            <th class="">{{ __('Status') }}</th>
                            <th class="">{{ __('Actions') }}</th>
                        </tr>
                        <!--end::Table row-->
                    </thead>
                    <!--end::Table head-->
                    <!--begin::Table body-->
                    <tbody class="text-gray-600 fw-bold">
                        @foreach($chatbots as $chatbot)
                            <!--begin::Table row-->
                            <tr>
                                <td>
                                    {{ $chatbot->name }} <br>
                                    <span class="text-gray-400">UUUID: {{ $chatbot->id }}</span>
                                </td>
                                <td><span class="badge badge-light-primary">{{ $chatbot->model }}</span></td>
                                <td>{{ $chatbot->temperature }}</td>
                                <td>{{ $chatbot->created_at->format('d/m/Y H:i') }}</td>
                                <td>
                                    @if($chatbot->status)
                                        <span class="badge badge-light-success">{{ __('Active') }}</span>                                        
                                    @else
                                        <span class="badge badge-light-secondary">{{ __('Inactive') }}</span>
                                    @endif
                                </td>
                                <!--begin::Action=-->
                                <td class="">
                                    <a href="{{ route('admin.chatbot.show', $chatbot) }}"
                                        class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1">
                                        <i class="fa-light fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.chatbot.edit', $chatbot) }}"
                                        class="btn btn-icon btn-bg-light btn-active-color-success btn-sm me-1">
                                        <i class="fa-light fa-pen-to-square"></i>
                                    </a>
                                    @include('admin.chatbot.chatbot.delete')
                                </td>
                                <!--end::Action=-->
                            </tr>
                            <!--end::Table row-->
                        @endforeach
                    </tbody>
                    <!--end::Table body-->
                </table>
            </div>
            <!--end::Table-->
            {{ $chatbots->links() }}
        </div>
        <!--end::Card body-->
    </div>
    <!--end::Card-->
</div>
