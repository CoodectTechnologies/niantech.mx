@extends('ecommerce.layouts.main')

@section('head')
    <title>{{ $product->getName() }} - {{ config('app.name') }}</title>
    <meta name="title" content="{{ $product->meta_title ?? $product->getName() }}" />
    <meta name="description" content="{{ $product->meta_description ?? $product->fragment }}" />
    <meta name="keywords" content="{{ $product->meta_keywords }}" />
    <meta http-equiv="title" content="{{ $product->getName() }}" />
    <meta property="og:title" content="{{ $product->getName() }}" />
    <meta property="og:description" content="{{ $product->getName() }}" />
    <meta property="og:url" content="{{ route('ecommerce.product.show', $product) }}" />
    <meta name="twitter:description" content="{{ $product->getName() }}" />
    <meta name="twitter:title" content="{{ $product->getName() }}" />
    <meta property="product:brand" content="{{ $product->brand ? $product->brand->name : '' }}" />
    <meta property="product:price:amount" content="{{ $product->getPriceFinal() }}" />
    <meta property="product:price:currency" content="{{ Session::get('currency') }}" />
@endsection

@section('content')
    <!-- Start of Breadcrumb -->
    <nav class="breadcrumb-nav container">
        <ul class="breadcrumb bb-no">
            <li><a href="{{ route('ecommerce.home.index') }}">{{ __('Home') }}</a></li>
            <li><a href="{{ route('ecommerce.product.index') }}">{{ __('Products') }}</a></li>
            <li class="active">{{ $product->getName() }}</li>
        </ul>
    </nav>
    <!-- End of Breadcrumb -->

    @livewire('ecommerce.product.show', ['product' => $product])
@endsection

@section('footer')
    <!-- Plugin JS File -->
    <script defer src="{{ asset('assets/ecommerce') }}/vendor/sticky/sticky.js"></script>
    <script defer src="{{ asset('assets/ecommerce') }}/vendor/zoom/jquery.zoom.js"></script>
    <script defer src="{{ asset('assets/ecommerce') }}/vendor/photoswipe/photoswipe.js"></script>
    <script defer src="{{ asset('assets/ecommerce') }}/vendor/photoswipe/photoswipe-ui-default.js"></script>
@endsection
