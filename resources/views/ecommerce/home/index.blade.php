@extends('ecommerce.layouts.main')

@section('head')
    <title>{{ __('Ninantech -  Computadoras Gamer y alto rendimiento') }}</title>
    <meta name="title" content="Ninantech -  Computadoras Gamer y alto rendimiento" />
    <meta name="description" content="Ninantech -  Computadoras Gamer y alto rendimiento" />
    <meta http-equiv="title" content="Ninantech -  Computadoras Gamer y alto rendimiento" />
    <meta property="og:title" content="Ninantech -  Computadoras Gamer y alto rendimiento" />
    <meta property="og:description" content="Ninantech -  Computadoras Gamer y alto rendimiento" />
    <meta name="description" content="Ninantech -  Computadoras Gamer y alto rendimiento" />
    <meta name="keywords" content="{{ config('app.name') }}, Ecommerce" />
    <meta property="og:url" content="{{ route('ecommerce.home.index') }}" />
    <meta name="twitter:description" content="Ninantech -  Computadoras Gamer y alto rendimiento" />
    <meta name="twitter:title" content="Ninantech -  Computadoras Gamer y alto rendimiento" />
@endsection

@section('body-class')
    class="home"
@endsection

@section('content')
    <div class="container">
        @include('ecommerce.home.partials._banner', ['banners' => $bannersPrimary])
        @include('ecommerce.home.partials.category-list')
        @include('ecommerce.home.partials._info')
        @include('ecommerce.home.partials._most-selled')
        @include('ecommerce.home.partials._featured')
        @include('ecommerce.home.partials._new')
        @include('ecommerce.home.partials._banner', ['banners' => $bannerCallToActions, 'btnDefault' => false, 'isDescriptionExplode' => true, 'classExtra' => ['mt-5']])
        @include('ecommerce.home.partials._partner')
        @include('ecommerce.home.partials._testimony')
        @include('ecommerce.home.partials._viewed')
        @include('ecommerce.home.partials._info-2')
    </div>
    @include('ecommerce.home.partials.popup')
    @include('ecommerce.components.cookies')
@endsection
