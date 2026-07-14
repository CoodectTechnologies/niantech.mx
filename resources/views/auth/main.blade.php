<!DOCTYPE html>
<html lang="en">
<!--begin::Head-->
 
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <!--begin::Fonts-->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" />
    <!--end::Fonts-->
    <!--begin::Global Stylesheets Bundle(used by all pages)-->
    <link href="{{ asset('assets/admin') }}/plugins/global/plugins.bundle.css" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/admin') }}/css/style.bundle.css" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/admin') }}/css/custom.css" rel="stylesheet" type="text/css" />
    {{-- <link href="{{ asset('assets/admin') }}/css/auth.css" rel="stylesheet" type="text/css" /> --}}
    <!--end::Global Stylesheets Bundle-->
    @yield('head')
</head>
<!--end::Head-->
<!--begin::Body-->

<body id="kt_body" class="bg-body">
    <script src="{{ asset('assets/admin/js/custom/theme/theme.js') }}"></script>
    <!--begin::Main-->
    <!--begin::Root-->
    <div class="">
        @yield('content')
    </div>
    <!--end::Root-->
    <!--end::Main-->
    <!--begin::Javascript-->
    <script>
        var hostUrl = "{{ asset('assets/admin') }}/";
    </script>
    <!--begin::Global Javascript Bundle(used by all pages)-->
    <script src="{{ asset('assets/admin') }}/plugins/global/plugins.bundle.js"></script>
    <script src="{{ asset('assets/admin') }}/js/scripts.bundle.js"></script>
    @vite(['resources/js/app.js'])
    <!--end::Global Javascript Bundle-->
    <!--end::Javascript-->
</body>
<!--end::Body-->

</html>
