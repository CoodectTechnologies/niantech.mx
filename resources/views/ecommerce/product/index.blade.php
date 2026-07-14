@extends('ecommerce.layouts.main')

@section('head')
    <title>{{ config('app.name') }} - Gran catálogo en laptops, pcs, headsets, redes, monitores, audio, accesorios, fuentes
        de poder, consolas, compra en línea.</title>
    <meta name="title" content="{{ $category->meta_title ?? __('Products') . '-' . config('app.name') }}" />
    <meta name="description"
        content="{{ config('app.name') . '- Compra gran catálogo en laptops, pcs, headsets, redes, monitores, audio, accesorios, fuentes de poder, consolas, controles, televisiones, telefónica, impresoras, consumibles y más. ' }}" />
    <meta name="keywords"
        content="{{ config('app.name') . '- Laptops, pcs, headsets, redes, monitores, audio, accesorios, fuentes de poder, consolas, controles, televisiones, telefónica, impresoras, consumibles, compra en línea, envío gratis, tecnología en un click.' }}" />
    <meta http-equiv="title" content="{{ $category->meta_title ?? __('Products') . '-' . config('app.name') }}" />
    <meta property="og:title" content="{{ $category->meta_title ?? __('Products') . '-' . config('app.name') }}" />
    <meta property="og:description"
        content="{{ $category->meta_description ?? __('Products') . '-' . config('app.name') }}" />
    <meta property="og:url" content="{{ route('ecommerce.product.index') }}" />
    <meta name="twitter:description"
        content="{{ $category->meta_description ?? __('Products') . '-' . config('app.name') }}" />
    <meta name="twitter:title" content="{{ $category->meta_description ?? __('Products') . '-' . config('app.name') }}" />
@endsection

@section('content')
    @if($category && $category->banner)
        <div class="container">
            <div class="shop-default-banner banner d-flex align-items-center mb-5 br-xs"
                style="background-image: url({{ $category->bannerPreview() }}); background-color: #FFC74E;">
                <div class="banner-content">
                    <h3 class="banner-title text-white text-uppercase font-weight-bolder ls-normal">{{ $category->name }}
                    </h3>
                </div>
            </div>
        </div>
    @endif
    @livewire('ecommerce.product.index', ['category' => $category])
@endsection
