<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700" />
    <link href="{{ asset('assets/admin/plugins/global/fonts/fontawesome-v7-pro/css/all.css') }}" rel="preload" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <link href="{{ asset('assets/admin/plugins/global/plugins.bundle.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/admin/css/style.bundle.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/admin/css/custom.css') }}" rel="stylesheet" type="text/css" />
    @include('admin.components.favicons')
    @livewireStyles
    @yield('head')
    @stack('head')
</head>
<body id="kt_app_body" data-kt-app-layout="dark-sidebar" data-kt-app-header-fixed="true"
	data-kt-app-sidebar-enabled="true" data-kt-app-sidebar-fixed="true" data-kt-app-sidebar-hoverable="true"
	data-kt-app-sidebar-push-header="true" data-kt-app-sidebar-push-toolbar="true"
	data-kt-app-sidebar-push-footer="true" data-kt-app-toolbar-enabled="true" class="safari-mode app-default">
    <script src="{{ asset('assets/admin/js/custom/theme/theme.js') }}"></script>
    <div class="d-flex flex-column flex-root app-root" id="kt_app_root">
        <div class="app-page flex-column flex-column-fluid" id="kt_app_page">
            @include('admin.layouts.header')
            <div class="app-wrapper flex-column flex-row-fluid" id="kt_app_wrapper">
                @include('admin.layouts.sidebar')
                <div class="app-main flex-column flex-row-fluid" id="kt_app_main">
                    <div class="d-flex flex-column flex-column-fluid">
                        @include('admin.layouts.breadcrumb')
                        <div id="kt_app_content" class="app-content  flex-column-fluid ">
                            @yield('content')
                        </div>
                    </div>
                    @include('admin.layouts.footer')
                </div>
            </div>
        </div>
    </div>
    <div id="kt_scrolltop" class="scrolltop" data-kt-scrolltop="true">
        <i class="ki-outline ki-arrow-up"></i>
    </div>
    <script>var hostUrl = "{{ asset('') }}";</script>
    <script src="{{ asset('assets/admin/plugins/global/plugins.bundle.js') }}"></script>
    <script src="{{ asset('assets/admin/js/scripts.bundle.js') }}"></script>
    @livewireScripts
    @vite(['resources/js/app.js'])
    @include('admin.components.alert-toastr')
    @include('admin.components.notification-push')
    @yield('footer')
    @stack('footer')
</body>
</html>
