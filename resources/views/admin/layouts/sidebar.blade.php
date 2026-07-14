<div id="kt_app_sidebar" class="app-sidebar  flex-column " data-kt-drawer="true"
     data-kt-drawer-name="app-sidebar" data-kt-drawer-activate="{default: true, lg: false}"
     data-kt-drawer-overlay="true" data-kt-drawer-width="225px" data-kt-drawer-direction="start"
     data-kt-drawer-toggle="#kt_app_sidebar_mobile_toggle">
    <!--begin::Header-->
    <div class="app-sidebar-logo d-flex flex-stack px-9 pt-10 pb-5" id="kt_app_sidebar_logo">
        <!--begin::Logo-->
        <a href="{{ route('admin.dashboard.general.index') }}">
            <img alt="Logo" src="{{ asset(config('app.logo')) }}" class="w-150px theme-light-show app-sidebar-logo-default" />
            <img alt="Logo" src="{{ asset(config('app.logo_white')) }}" class="w-150px theme-dark-show app-sidebar-logo-default" />

            <img alt="Logo" src="{{ asset(config('app.logo_favicon')) }}" class="w-20px theme-light-show app-sidebar-logo-minimize" />
            <img alt="Logo" src="{{ asset(config('app.logo_favicon')) }}" class="w-20px theme-dark-show app-sidebar-logo-minimize" />
        </a>
        <!--end::Logo-->
        <!--begin::Notifications-->
        <div class="d-none d-lg-block">
            @livewire('admin.layouts.notification', ['lazy' => true], key('notification-desktop'))
        </div>
        <!--end::Notifications-->
        <div id="kt_app_sidebar_toggle"
            class="app-sidebar-toggle btn btn-icon btn-shadow btn-sm btn-color-muted btn-active-color-primary h-30px w-30px position-absolute start-100 translate-middle rotate mt-10"
            data-kt-toggle="true" data-kt-toggle-state="active" data-kt-toggle-target="body"
            data-kt-toggle-name="app-sidebar-minimize">
            <i class="ki-duotone ki-black-left-line fs-3 rotate-180 d-none d-lg-block">
                <span class="path1"></span>
                <span class="path2"></span>
            </i>
        </div>
        <!--end::Sidebar toggle-->
    </div>
    <!--end::Header-->
    <!--begin::sidebar menu-->
    <div class="app-sidebar-menu overflow-hidden flex-column-fluid">
        <!--begin::Menu wrapper-->
        <div id="kt_app_sidebar_menu_wrapper" class="app-sidebar-wrapper">
            <!--begin::Scroll wrapper-->
            <div id="kt_app_sidebar_menu_scroll" class="scroll-y mx-3" data-kt-scroll="true"
                data-kt-scroll-activate="true" data-kt-scroll-height="auto"
                data-kt-scroll-dependencies="#kt_app_sidebar_logo, #kt_app_sidebar_footer"
                data-kt-scroll-wrappers="#kt_app_sidebar_menu" data-kt-scroll-offset="5px"
                data-kt-scroll-save-state="true">
                @include('admin.layouts.menu')
            </div>
            <!--end::Scroll wrapper-->
        </div>
        <!--end::Menu wrapper-->
    </div>
    <!--end::sidebar menu-->
    <div class="app-sidebar-footer d-flex flex-stack px-10 py-10" id="kt_app_sidebar_footer">
        <!--begin::User-->
        <div class="me-2">
            <!--begin::User info-->
            <div class="d-flex align-items-center" data-kt-menu-trigger="{default: 'click', lg: 'hover'}"
                data-kt-menu-overflow="true" data-kt-menu-placement="top-start">
                <div class="d-flex flex-center cursor-pointer symbol symbol-circle symbol-40px">
                    <img src="{{ auth()->user()->imagePreview() }}" alt="image" />
                </div>
                <!--begin::Name-->
                <div class="d-flex flex-column align-items-start justify-content-center ms-3">
                    <span class="text-gray-500 fs-8 fw-semibold">{{ __('Hi') }}</span>
                    <a href="#"
                        class="text-gray-800 fs-7 fw-bold text-hover-primary">{{ explode(' ', auth()->user()->name)[0] }}</a>
                </div>
                <!--end::Name-->
            </div>
            <!--end::User info-->
            <!--begin::User account menu-->
            <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg menu-state-color fw-semibold py-4 fs-6 w-275px"
                data-kt-menu="true">
                <!--begin::Menu item-->
                <div class="menu-item px-3">
                    <div class="menu-content d-flex align-items-center px-3">
                        <!--begin::Avatar-->
                        <div class="symbol symbol-50px me-5">
                            <img alt="Logo" src="{{ auth()->user()->imagePreview() }}" />
                        </div>
                        <!--end::Avatar-->
                        <!--begin::Username-->
                        <div class="d-flex flex-column">
                            <div class="fw-bold d-flex align-items-center fs-5">{{ auth()->user()->name }}
                                {{-- <span class="badge badge-light-success fw-bold fs-8 px-2 py-1 ms-2">Pro</span> --}}
                            </div>
                            <a href="#"
                                class="fw-semibold text-muted text-hover-primary fs-7">{{ auth()->user()->email }}</a>
                        </div>
                        <!--end::Username-->
                    </div>
                </div>
                <!--end::Menu item-->
                <!--begin::Menu separator-->
                <div class="separator my-2"></div>
                <!--end::Menu separator-->
                <!--begin::Menu item-->
                <div class="menu-item px-5">
                    <a href="{{ route('admin.user.profile') }}" class="menu-link px-5">{{ __('Profile') }}</a>
                </div>
                <!--end::Menu item-->
                <!--begin::Menu separator-->
                <div class="separator my-2"></div>
                <!--end::Menu separator-->
                <!--begin::Menu item-->
                <div class="menu-item px-5" data-kt-menu-trigger="{default: 'click', lg: 'hover'}"
                    data-kt-menu-placement="left-start" data-kt-menu-offset="-15px, 0">
                    <a href="#" class="menu-link px-5">
                        <span class="menu-title position-relative">{{ __('Theme') }}
                            <span class="ms-5 position-absolute translate-middle-y top-50 end-0">
                                <i class="ki-outline ki-night-day theme-light-show fs-2"></i>
                                <i class="ki-outline ki-moon theme-dark-show fs-2"></i>
                            </span></span>
                    </a>
                    <!--begin::Menu-->
                    <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-title-gray-700 menu-icon-gray-500 menu-active-bg menu-state-color fw-semibold py-4 fs-base w-150px"
                        data-kt-menu="true" data-kt-element="theme-mode-menu">
                        <!--begin::Menu item-->
                        <div class="menu-item px-3 my-0">
                            <a href="#" class="menu-link px-3 py-2" data-kt-element="mode"
                                data-kt-value="light">
                                <span class="menu-icon" data-kt-element="icon">
                                    <i class="ki-outline ki-night-day fs-2"></i>
                                </span>
                                <span class="menu-title">{{ __('Light') }}</span>
                            </a>
                        </div>
                        <!--end::Menu item-->
                        <!--begin::Menu item-->
                        <div class="menu-item px-3 my-0">
                            <a href="#" class="menu-link px-3 py-2" data-kt-element="mode"
                                data-kt-value="dark">
                                <span class="menu-icon" data-kt-element="icon">
                                    <i class="ki-outline ki-moon fs-2"></i>
                                </span>
                                <span class="menu-title">{{ __('Dark') }}</span>
                            </a>
                        </div>
                        <!--end::Menu item-->
                        <!--begin::Menu item-->
                        <div class="menu-item px-3 my-0">
                            <a href="#" class="menu-link px-3 py-2" data-kt-element="mode"
                                data-kt-value="system">
                                <span class="menu-icon" data-kt-element="icon">
                                    <i class="ki-outline ki-screen fs-2"></i>
                                </span>
                                <span class="menu-title">{{ __('System') }}</span>
                            </a>
                        </div>
                        <!--end::Menu item-->
                    </div>
                    <!--end::Menu-->
                </div>
                <!--end::Menu item-->
                @if(count(languages()) && Route::has('admin.language'))
                    <div class="menu-item px-5" data-kt-menu-trigger="{default: 'click', lg: 'hover'}"
                        data-kt-menu-placement="right-end" data-kt-menu-offset="-15px, 0">
                        <a href="#" class="menu-link px-5">
                            <span class="menu-title position-relative">
                                {{ __('Lenguaje') }}
                                <span
                                    class="fs-8 rounded bg-light px-3 py-2 position-absolute translate-middle-y top-50 end-0">
                                    {{ Str::upper(language()) }} <img class="w-15px h-15px rounded-1 ms-2"
                                        src="{{ languages()[language()]['flag'] }}" alt="" />
                                </span>
                            </span>
                        </a>
                        <div class="menu-sub menu-sub-dropdown w-175px py-4">
                            @foreach(languages() as $locale => $language)
                                <div class="menu-item px-3">
                                    <a href="{{ route('admin.language', $locale) }}"
                                        class="menu-link d-flex px-5 {{ language() == $locale ? 'active' : '' }}">
                                        <span class="symbol symbol-20px me-4">
                                            <img class="rounded-1" src="{{ $language['flag'] }}" alt="" />
                                        </span>
                                        {{ Str::upper($locale) }}
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
                <!--begin::Menu item-->
                <div class="menu-item px-5 my-1">
                    <a href="{{ route('admin.notification.index') }}" class="menu-link px-5">
                        {{ __('Notifications') }}
                    </a>
                </div>
                <div class="menu-item px-5">
                    <a href="#"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                        class="menu-link px-5">{{ __('Log out') }}</a>
                </div>
                <!--end::Menu item-->
            </div>
            <!--end::User account menu-->
        </div>
        <!--end::User-->
        <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
            class="btn btn-icon btn-color-gray-500 btn-active-color-primary me-n7">
            <i class="ki-outline ki-exit-right fs-2"></i>
        </a>

        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
            @csrf
        </form>
    </div>
</div>
