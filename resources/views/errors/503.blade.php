@extends('ecommerce.layouts.main')

@section('head')
    <title>{{ __('Error page') }} 503</title>
@endsection

@section('content')
    <!-- Start of Breadcrumb -->
    <nav class="breadcrumb-nav">
        <div class="container">
            <ul class="breadcrumb">
                <li><a href="{{ route('ecommerce.home.index') }}">{{ __('Home') }}</a></li>
                <li>{{ __('Error page') }} 503</li>
            </ul>
        </div>
    </nav>
    <!-- End of Breadcrumb -->

    <!-- Start of Page Content -->
    <div class="page-content error-404">
        <div class="container">
            <div class="banner">
                {{-- <figure>
                    <img loading="lazy" src="{{ asset('assets/ecommerce/images/errors/503.jpg') }}" alt="Error 503"
                        width="820" height="460" />
                </figure> --}}
                <div class="banner-content text-center">
                    <h2 class="banner-title">
                        <span class="text-secondary">{{ __('Oops!!!') }}</span> {{ __('Page in maintenance') }}
                    </h2>
                    <p class="text-light">{{ __('We are making improvements to our website') }}</p>
                </div>
            </div>
        </div>
    </div>
    <!-- End of Page Content -->
@endsection
