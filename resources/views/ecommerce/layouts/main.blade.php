<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <!--================================================
        META
        =================================================-->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=5, user-scalable=yes">
        <meta name="description" content="Niantech - Tienda online especializada en tecnología, PC Gamer, componentes, laptops, periféricos y accesorios electrónicos. Calidad, rendimiento y los mejores precios.">
        <meta name="keywords" content="Niantech, gaming, pc, gaming pc, peripherals, games, accesories, gamer, chairs, monitor, headsets, gamer furniture, build a pc, build a gaming pc, building a pc, building a gaming pc, custom pc builder, gaming pc build, pc building simulator, pc builder website, how to build a pc, how to build a gaming pc, best pre built gaming pc, best prebuilt gaming pc, pre built gaming pc, prebuilt gaming pc, ibuypower, nzxt, skytech, cheap gaming pc, lyte gaming pc, pc gaming, best gaming pc, gaming pc cheat, best buy gaming pc, ibuypower gaming pc, pc gaming setup, custom gaming pc, cyberpower gaming pc, gaming pc build, hp gaming pc, cyberpower, pc gaming reddit, budget gaming pc, nvidia geforce, gaming pc and monitor, gaming pc for sale, good gaming pc, pc gaming computer, pc gaming set up, RTX, rtx 4090, geforce 4090, rtx 4090, Yeyian Gaming Desktop, yeyian gaming, yeyian gaming pc, yeyian usa, yeyian, yeyian odachi, yeyian Odachi 795DC-490, Odachi 795DC-490 gaming pc, Odachi 795DC-490 PC, yeyian desktop, gaming desktop, yeyin gaming pc, yeyian pc, yeiyan, AMD processors, AMD Ryzen 9, AMD, AMD gaming pc, AMD desktop, AMD gaming computer, streaming pc, streamer computer, streaming desktop, streamer setup, what do you need to build a pc, how much does it cost to build a pc, how to build a pc for gaming, how to build your own pc, what do i need to build a gaming pc, what parts do you need to build a pc, how to build pc gamer, how to build your own gaming pc, how to build a pc step by step, what parts do i need to build a gaming pc" />
        <meta name="author" content="{{ config('app.url') }}">
        <meta name="robots" content="index, follow">
        <meta name="Revisit" content="2 days" />
        <meta name="language" content="{{ str_replace('_', '-', app()->getLocale()) }}" />
        <!--================================================
        SCHEMA MARKUP FOR GOOGLE
        =================================================-->
        <meta itemprop="name" content="Niantech -  Computadoras Gamer y alto rendimiento" />
        <meta itemprop="description" content="Niantech - Tienda online especializada en tecnología, PC Gamer, componentes, laptops, periféricos y accesorios electrónicos. Calidad, rendimiento y los mejores precios." />
        <meta itemprop="image" content="{{ asset('assets/admin/media/logo/logo_favicon.webp') }}" />
        <!--================================================
        OPEN GRAPH DATA
        =================================================-->
        <meta property="og:locale" content="{{ str_replace('_', '-', app()->getLocale()) }}" />
        <meta property="og:title" content="Niantech -  Computadoras Gamer y alto rendimiento" />
        <meta property="og:description" content="Niantech - Tienda online especializada en tecnología, PC Gamer, componentes, laptops, periféricos y accesorios electrónicos. Calidad, rendimiento y los mejores precios." />
        <meta property="og:type" content="website" />
        <meta property="og:url" content="{{ config('app.url') }}" />
        <meta property="og:site_name" content="{{ config('app.name') }}" />
        <meta property="og:image" content="{{ asset('assets/admin/media/logo/logo_favicon.webp') }}" />
        <meta property="og:image:secure_url" content="{{ asset('assets/admin/media/logo/logo_favicon.webp') }}" />
        <meta property="og:image:width" content="200" />
        <meta property="og:image:height" content="200" />
        <meta property="og:image:type" content="image/png" />
        <meta property="og:image:alt" content="{{ config('app.name') }}" />
        <!--================================================
        TWITTER CARD DATA
        =================================================-->
        <meta name="twitter:title" content="Niantech -  Computadoras Gamer y alto rendimiento" />
        <meta name="twitter:description" content="Niantech - Tienda online especializada en tecnología, PC Gamer, componentes, laptops, periféricos y accesorios electrónicos. Calidad, rendimiento y los mejores precios." />
        <meta name="twitter:image" content="{{ asset('assets/admin/media/logo/logo_favicon.webp') }}" />
        <meta name="twitter:card" content="summary" />
        <meta name="twitter:site" content="@Niantech" />
        <meta name="twitter:creator" content="@Niantech" />
        <!--================================================
        FONTS
        =================================================-->
        <link rel="preload" href="{{ asset('assets/ecommerce') }}/vendor/fontawesome-free/webfonts/fa-regular-400.woff2" as="font" type="font/woff2" crossorigin="anonymous">
        <link rel="preload" href="{{ asset('assets/ecommerce') }}/vendor/fontawesome-free/webfonts/fa-solid-900.woff2" as="font" type="font/woff2" crossorigin="anonymous">
        <link rel="preload" href="{{ asset('assets/ecommerce') }}/vendor/fontawesome-free/webfonts/fa-brands-400.woff2" as="font" type="font/woff2" crossorigin="anonymous">
        <link rel="preload" href="{{ asset('assets/ecommerce') }}/fonts/wolmart.ttf?png09e" as="font" type="font/ttf" crossorigin="anonymous">
        <!--================================================
        CSS
        =================================================-->
        <link rel="stylesheet" href="{{ asset('assets/ecommerce/css/bootstrap/bootstrap.min.css') }}">
        <link href="{{ asset('assets/admin/plugins/global/fonts/fontawesome-v7-pro/css/all.css') }}" rel="preload" as="style" onload="this.onload=null;this.rel='stylesheet'">
        <link rel="preload" as="style" onload="this.onload=null;this.rel='stylesheet'" href="{{ asset('assets/ecommerce') }}/vendor/owl-carousel/owl.carousel.min.css">
        <link rel="preload" as="style" onload="this.onload=null;this.rel='stylesheet'" href="{{ asset('assets/ecommerce') }}/vendor/magnific-popup/magnific-popup.min.css">
        <link rel="preload" as="style" onload="this.onload=null;this.rel='stylesheet'" href="{{ asset('assets/ecommerce') }}/vendor/photoswipe/photoswipe.min.css">
        <link rel="preload" as="style" onload="this.onload=null;this.rel='stylesheet'" href="{{ asset('assets/ecommerce') }}/vendor/photoswipe/default-skin/default-skin.min.css">
        <link rel="preload" as="style" onload="this.onload=null;this.rel='stylesheet'" rel="stylesheet" href="{{ asset('assets/ecommerce/css/style.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/ecommerce/css/custom.css') }}">
        <!--================================================
        FAVICONS
        =================================================-->
        @include('admin.components.favicons')
        <!--================================================
        LIVEWIRE CSS
        =================================================-->
        @livewireStyles
        <!--================================================
        CUSTOM
        =================================================-->
        @yield('head')
        @stack('head')
        <x-web.layouts.tag-analytic-header />
    </head>
    <body @yield('body-class')>
        <x-web.layouts.tag-analytic-footer />
        <script src="{{ asset('assets/admin/js/custom/theme/theme.js') }}"></script>
        <div class="page-wrapper">
            @include('ecommerce.layouts.header')
            <main class="main">
                @yield('content')
            </main>
            @include('ecommerce.layouts.footer')
        </div>
        <a id="scroll-top" href="#top" title="Top" role="button" class="scroll-top"><i class="fas fa-chevron-up"></i></a>
        @include('ecommerce.layouts.menu-mobile.index')
        <!--================================================
        RICH SNIPPET
        =================================================-->
        @verbatim
        <script type='application/ld+json'>
            {
                "@context": "http://www.schema.org",
                "@type": "Organization",
                "name": "{{ config('app.name') }}",
                "url": "{{ config('app.url') }}",
                "logo": "{{ asset(config('app.logo')) }}",
                "description": "",
                "address": {
                    "@type": "PostalAddress",
                    "streetAddress": "",
                    "addressLocality": "",
                    "addressRegion": "",
                    "postalCode": "",
                    "addressCountry": ""
                },
                "geo": {
                    "@type": "GeoCoordinates"
                },
                "openingHours": "Mo, Tu, We, Th, Fr 09:00-18:00",
                "contactPoint": {
                    "@type": "ContactPoint",
                    "contactType": "sales",
                    "telephone": "{{ config('contact.phone') }}",
                    "email": "{{ config('contact.email') }}",
                    "url": "{{ config('app.url') }}"
                }
            }
        </script>
        @endverbatim
    </body>
    <!--================================================
    JAVASCRIPT
    =================================================-->
    <noscript>Your browser does not support JavaScript!</noscript>
    <script src="{{ asset('assets/ecommerce') }}/vendor/jquery/jquery.min.js"></script>
    <script src="{{ asset('assets/ecommerce/js/bootstrap/bootstrap.min.js') }}"></script>
    <script defer src="{{ asset('assets/ecommerce') }}/vendor/jquery.plugin/jquery.plugin.min.js"></script>
    <script defer src="{{ asset('assets/ecommerce') }}/vendor/imagesloaded/imagesloaded.pkgd.min.js"></script>
    <script defer src="{{ asset('assets/ecommerce') }}/vendor/owl-carousel/owl.carousel.min.js"></script>
    <script defer src="{{ asset('assets/ecommerce') }}/vendor/jquery.countdown/jquery.countdown.min.js"></script>
    <script defer src="{{ asset('assets/ecommerce') }}/vendor/magnific-popup/jquery.magnific-popup.min.js"></script>
    <script defer src="{{ asset('assets/ecommerce') }}/vendor/floating-parallax/parallax.min.js"></script>
    <script defer src="{{ asset('assets/ecommerce') }}/js/main.js"></script>
    <script defer src="{{ asset('assets/ecommerce') }}/js/custom.js"></script>
    <!--================================================
    LIVEWIRE JAVASCRIPT
    =================================================-->
    @livewireScripts
    <!--================================================
    CUSTOM JAVASCRIPT
    =================================================-->
    @vite(['resources/js/app.js'])
    @include('ecommerce.components.cart-added')
    @include('ecommerce.components.toastr')
    @yield('footer')
    @stack('footer')
</html>
