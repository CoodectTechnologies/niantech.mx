<!--begin::Header-->
<div id="kt_app_header" class="app-header d-flex d-lg-none border-bottom">
    <div class="app-container container-fluid d-flex flex-stack" id="kt_app_header_container">
        <!--begin::Sidebar toggle-->
        <button class="btn btn-icon btn-sm btn-active-color-primary ms-n2" id="kt_app_sidebar_mobile_toggle">
            <i class="ki-outline ki-abstract-14 fs-2"></i>
        </button>
        <!--end::Sidebar toggle-->
        <!--begin::Logo-->
        <a href="{{ route('admin.dashboard.general.index') }}">
            <img alt="Logo" src="{{ asset(config('app.logo')) }}" class="h-30px theme-light-show" />
            <img alt="Logo" src="{{ asset(config('app.logo_white')) }}" class="h-30px theme-dark-show" />
        </a>
        <!--end::Logo-->
        <!--begin::Sidebar panel toggle-->
        <div class="d-block d-lg-none me-4">
            @livewire('admin.layouts.notification', ['lazy' => true], key('notification-mobile'))
        </div>
        <!--end::Sidebar panel toggle-->
    </div>
</div>
<!--end::Header-->
