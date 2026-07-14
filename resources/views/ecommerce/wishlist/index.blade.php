@extends('ecommerce.layouts.main')

@section('head')
    <title>{{ __('Wishlist') }} - {{ config('app.name') }}</title>
    <meta name="title" content="{{ config('app.name') }} - Carrito" />
    <meta name="description" content="{{ config('app.name') }} - Carrito" />
    <meta http-equiv="title" content="{{ config('app.name') }} - Carrito" />
    <meta property="og:title" content="{{ config('app.name') }} - Carrito" />
    <meta property="og:description" content="{{ config('app.name') }} - Carrito" />
    <meta name="description" content="{{ config('app.name') }} - Carrito" />
    <meta name="keywords" content="{{ config('app.name') }}, Carrito" />
    <meta property="og:url" content="{{ route('ecommerce.cart.index') }}" />
    <meta name="twitter:description" content="{{ config('app.name') }} - Carrito" />
    <meta name="twitter:title" content="{{ config('app.name') }} - Carrito" />
@endsection

@section('content')
    <!-- Start of Breadcrumb -->
    <nav class="breadcrumb-nav mb-10">
        <div class="container">
            <ul class="breadcrumb">
                <li><a href="{{ route('ecommerce.home.index') }}">{{ __('Home') }}</a></li>
                <li>{{ __('Wishlist') }}</li>
            </ul>
        </div>
    </nav>
    <!-- End of Breadcrumb -->

    @include('ecommerce.components.alert')

    @livewire('ecommerce.wishlist.index')
@endsection
