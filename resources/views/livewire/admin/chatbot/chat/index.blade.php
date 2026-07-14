<div>
    <div class="card card-flush" style="min-height: 75vh;">
    <!--begin::Card header-->
        <div class="card-header pt-7 w-100" id="kt_chat_contacts_header">
            <!--begin::Card title-->
            <div class="card-title w-100">
                <!--begin::Search-->
                <div class="d-flex align-items-center position-relative my-1 w-100">
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
                        class="form-control form-control-solid w-100 ps-14" placeholder="{{ __('Search...') }}" />
                </div>
                <!--end::Search-->
            </div>
            <!--begin::Card title-->
        </div>
        <!--end::Card header-->

        <!--begin::Card body-->
        <div class="card-body pt-5" id="kt_chat_contacts_body">
            <!--begin::List-->
            <div class="scroll-y me-n5 pe-5 h-200px h-lg-auto" data-kt-scroll="true"
                data-kt-scroll-activate="{default: false, lg: true}" data-kt-scroll-max-height="auto"
                data-kt-scroll-dependencies="#kt_header, #kt_app_header, #kt_toolbar, #kt_app_toolbar, #kt_footer, #kt_app_footer, #kt_chat_contacts_header"
                data-kt-scroll-wrappers="#kt_content, #kt_app_content, #kt_chat_contacts_body"
                data-kt-scroll-offset="5px" style="max-height: 745px">

                @foreach($chats as $chat)
                    <!--begin::User-->
                    <div wire:click='selectChat("{{ $chat->id }}")' class="d-flex flex-stack py-4" style="cursor: pointer">
                        <!--begin::Details-->
                        <div class="d-flex align-items-center">
                            <!--begin::Avatar-->
                            <div class="symbol symbol-45px symbol-circle">
                                <span class="symbol-label bg-light-danger text-danger fs-6 fw-bolder">M</span>
                                <div class="symbol-badge bg-success start-100 top-100 border-4 h-8px w-8px ms-n2 mt-n2">
                                </div>
                            </div>
                            <!--end::Avatar-->
                            <!--begin::Details-->
                            <div class="ms-5">
                                <a href="#" class="fs-5 fw-bold text-gray-900 text-hover-primary mb-2">{{ $chat->user->name ?? 'Anonimo' }}</a>
                                <div class="fw-semibold text-muted">{{ $chat->user->email ?? 'N/A' }}</div>
                            </div>
                            <!--end::Details-->
                        </div>
                        <!--end::Details-->

                        <!--begin::Lat seen-->
                        <div class="d-flex flex-column align-items-end ms-2">
                            <span wire:loading.remove wire:target="selectChat('{{ $chat->id }}')" class="text-muted fs-7 mb-1">{{ $chat->updated_at->diffForHumans() }}</span>
                            <span wire:loading wire:target="selectChat('{{ $chat->id }}')" class="spinner-border spinner-border-sm text-primary"></span>
                        </div>
                        <!--end::Lat seen-->
                    </div>
                    <!--end::User-->
                    <!--begin::Separator-->
                    <div class="separator separator-dashed d-none"></div>
                    <!--end::Separator-->
                @endforeach
            </div>
            <!--end::List-->
        </div>
        <!--end::Card body-->
    </div>
</div>
