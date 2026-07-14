<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <!--================================================
	META
	=================================================-->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" />
    <meta name="description" content="Notaría Número 7 de Tlaquepaque, Jalisco. Servicio notarial confiable, transparente y responsable. Compra-venta, testamentos, herencias, sociedades y trámites legales con certeza jurídica." />
    <meta name="keywords" content="Notaría en Tlaquepaque, Notario en Zapopan, servicios notariales Jalisco, compra venta de inmuebles, testamentos, juicios sucesorios, constitución de sociedades, poderes notariales" />
    <meta name="author" content="{{ config('app.url') }}">
    <meta name="robots" content="index, follow">
    <meta name="Revisit" content="2 days" />
    <meta name="language" content="{{ str_replace('_', '-', app()->getLocale()) }}"/>
	<!--================================================
	SCHEMA MARKUP FOR GOOGLE
	=================================================-->
    <meta itemprop="name" content="Notaría Número 7 de Tlaquepaque | Lic. Salvador Guillermo Plaza Arana" />
    <meta itemprop="description" content="Servicios notariales con certeza jurídica, responsabilidad y trato humano en Zapopan, Jalisco. Atención profesional en trámites patrimoniales, familiares y empresariales." />
    <meta itemprop="image" content="{{ asset('assets/admin/media/logo/logo_favicon.webp') }}" />
	<!--================================================
	OPEN GRAPH DATA
	=================================================-->
    <meta property="og:locale" content="{{ str_replace('_', '-', app()->getLocale()) }}" />
    <meta property="og:title" content="Notaría Número 7 de Tlaquepaque | Servicio Notarial Confiable" />
    <meta property="og:description" content="Brindamos servicios notariales con transparencia, profesionalismo y compromiso ético. Compra-venta, testamentos, herencias, sociedades y más en Jalisco." />
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
    <meta name="twitter:title" content="Notaría Número 7 de Tlaquepaque | Lic. Salvador Guillermo Plaza Arana" />
    <meta name="twitter:description" content="Servicios notariales profesionales en Zapopan, Jalisco. Certeza jurídica en trámites personales, patrimoniales y empresariales." />
    <meta name="twitter:image" content="{{ asset('assets/admin/media/logo/logo_favicon.webp') }}" />
    <meta name="twitter:card" content="summary" />
    <!--================================================
	FONTS
	=================================================-->
    <link rel="preconnect" href="https://fonts.googleapis.com/">
    <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,100;1,300;1,400;1,700;1,900&amp;display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400..900;1,400..900&amp;display=swap" rel="stylesheet">
    <!--================================================
	CSS
	=================================================-->
    <link rel="stylesheet" href="{{ asset('assets/web/css') }}/bootstrap.min.css" />
    <link rel="stylesheet" href="{{ asset('assets/web/css') }}/animate.min.css" />
    <link rel="stylesheet" href="{{ asset('assets/web/css') }}/custom-animate.css" />
    <link rel="stylesheet" href="{{ asset('assets/web/css') }}/swiper.min.css" />
    <link rel="stylesheet" href="{{ asset('assets/web/css') }}/font-awesome-all.css" />
    <link rel="stylesheet" href="{{ asset('assets/web/css') }}/jarallax.css" />
    <link rel="stylesheet" href="{{ asset('assets/web/css') }}/jquery.magnific-popup.css" />
    <link rel="stylesheet" href="{{ asset('assets/web/css') }}/odometer.min.css" />
    <link rel="stylesheet" href="{{ asset('assets/web/css') }}/flaticon.css">
    <link rel="stylesheet" href="{{ asset('assets/web/css') }}/owl.carousel.min.css" />
    <link rel="stylesheet" href="{{ asset('assets/web/css') }}/owl.theme.default.min.css" />
    <link rel="stylesheet" href="{{ asset('assets/web/css') }}/nice-select.css" />
    <link rel="stylesheet" href="{{ asset('assets/web/css') }}/jquery-ui.css" />
    <link rel="stylesheet" href="{{ asset('assets/web/css') }}/twentytwenty.css" />
    <link rel="stylesheet" href="{{ asset('assets/web/css') }}/module-css/slider.css" />
    <link rel="stylesheet" href="{{ asset('assets/web/css') }}/module-css/footer.css" />
    <link rel="stylesheet" href="{{ asset('assets/web/css') }}/module-css/about.css" />
    <link rel="stylesheet" href="{{ asset('assets/web/css') }}/module-css/service.css" />
    <link rel="stylesheet" href="{{ asset('assets/web/css') }}/module-css/why-choose.css" />
    <link rel="stylesheet" href="{{ asset('assets/web/css') }}/module-css/testimonial.css" />
    <link rel="stylesheet" href="{{ asset('assets/web/css') }}/module-css/case.css" />
    <link rel="stylesheet" href="{{ asset('assets/web/css') }}/module-css/counter.css" />
    <link rel="stylesheet" href="{{ asset('assets/web/css') }}/module-css/team.css" />
    <link rel="stylesheet" href="{{ asset('assets/web/css') }}/module-css/pricing.css" />
    <link rel="stylesheet" href="{{ asset('assets/web/css') }}/module-css/process.css" />
    <link rel="stylesheet" href="{{ asset('assets/web/css') }}/module-css/sliding-text.css" />
    <link rel="stylesheet" href="{{ asset('assets/web/css') }}/module-css/video.css" />
    <link rel="stylesheet" href="{{ asset('assets/web/css') }}/module-css/brand.css" />
    <link rel="stylesheet" href="{{ asset('assets/web/css') }}/module-css/blog.css" />
    <link rel="stylesheet" href="{{ asset('assets/web/css') }}/module-css/faq.css" />
    <link rel="stylesheet" href="{{ asset('assets/web/css') }}/module-css/skill.css" />    
    <link rel="stylesheet" href="{{ asset('assets/web/css') }}/module-css/page-header.css" />
    <link rel="stylesheet" href="{{ asset('assets/web/css') }}/module-css/feature.css" />
    <link rel="stylesheet" href="{{ asset('assets/web/css') }}/module-css/contact.css" />
    <link rel="stylesheet" href="{{ asset('assets/web/css') }}/module-css/newsletter.css" />
    <link rel="stylesheet" href="{{ asset('assets/web/css') }}/style.css" />
    <link rel="stylesheet" href="{{ asset('assets/web/css') }}/responsive.css" />
    <!--================================================
	NOSCRIPTS
	=================================================-->
    <noscript>Your browser does not support JavaScript!</noscript>
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

<body class="custom-cursor">
    <x-web.layouts.tag-analytic-footer />

    <div class="custom-cursor__cursor"></div>
    <div class="custom-cursor__cursor-two"></div>
    <div class="loader js-preloader">
        <div></div>
        <div></div>
        <div></div>
    </div>
    
    <div class="page-wrapper">
        @include('web.layouts.header')
        @yield('content')
        @include('web.layouts.footer')
    </div>

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
            "description": "Notaría Número 7 de Tlaquepaque, Jalisco. Servicio notarial confiable, transparente y responsable. Compra-venta, testamentos, herencias, sociedades y trámites legales con certeza jurídica.",
            "address": {
                "@type": "PostalAddress",
                "streetAddress": "Real de tesistan",
                "addressLocality": "Zapopan",
                "addressRegion": "Jalisco",
                "postalCode": "45200",
                "addressCountry": "México"
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
    <!--================================================
	JAVASCRIPT
	=================================================-->
    <script src="{{ asset('assets/web') }}/js/jquery-latest.js"></script>
    <script src="{{ asset('assets/web') }}/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('assets/web') }}/js/jarallax.min.js"></script>
    <script src="{{ asset('assets/web') }}/js/jquery.ajaxchimp.min.js"></script>
    <script src="{{ asset('assets/web') }}/js/jquery.appear.min.js"></script>
    <script src="{{ asset('assets/web') }}/js/swiper.min.js"></script>
    <script src="{{ asset('assets/web') }}/js/jquery.circle-progress.min.js"></script>
    <script src="{{ asset('assets/web') }}/js/knob.js"></script>
    <script src="{{ asset('assets/web') }}/js/jquery.magnific-popup.min.js"></script>
    <script src="{{ asset('assets/web') }}/js/jquery.validate.min.js"></script>
    <script src="{{ asset('assets/web') }}/js/odometer.min.js"></script>
    <script src="{{ asset('assets/web') }}/js/wNumb.min.js"></script>
    <script src="{{ asset('assets/web') }}/js/wow.js"></script>
    <script src="{{ asset('assets/web') }}/js/isotope.js"></script>
    <script src="{{ asset('assets/web') }}/js/owl.carousel.min.js"></script>
    <script src="{{ asset('assets/web') }}/js/jquery-ui.js"></script>
    <script src="{{ asset('assets/web') }}/js/jquery.circleType.js"></script>
    <script src="{{ asset('assets/web') }}/js/jquery.lettering.min.js"></script>
    <script src="{{ asset('assets/web') }}/js/jquery.fittext.js"></script>
    <script src="{{ asset('assets/web') }}/js/jquery.nice-select.min.js"></script>
    <script src="{{ asset('assets/web') }}/js/marquee.min.js"></script>
    <script src="{{ asset('assets/web') }}/js/countdown.min.js"></script>
    <script src="{{ asset('assets/web') }}/js/jquery-sidebar-content.js"></script>
    <script src="{{ asset('assets/web') }}/js/twentytwenty.js"></script>
    <script src="{{ asset('assets/web') }}/js/jquery.event.move.js"></script>
    <script src="{{ asset('assets/web') }}/js/gsap/gsap.js"></script>
    <script src="{{ asset('assets/web') }}/js/gsap/ScrollTrigger.js"></script>
    <script src="{{ asset('assets/web') }}/js/gsap/SplitText.js"></script>
    <script src="{{ asset('assets/web') }}/js/script.js"></script>
    <!--================================================
	LIVEWIRE JAVASCRIPT
	=================================================-->
    @livewireScripts
    <!--================================================
    CUSTOM JAVASCRIPT
    =================================================-->
    @yield('footer')
	@stack('footer')
</body>
</html>
