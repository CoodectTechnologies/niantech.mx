@extends('ecommerce.layouts.main')

@section('head')
    <title>{{ $privacyNotice->name }} - {{ config('app.name') }}</title>
@endsection

@section('content')
    <nav class="breadcrumb-nav">
        <div class="container">
            <ul class="breadcrumb bb-no">
                <li><a href="{{ route('ecommerce.home.index') }}">{{ __('Home') }}</a></li>
                <li class="active">{{ $privacyNotice->name }}</li>
            </ul>
        </div>
    </nav>
    <!-- End of Breadcrumb-nav -->

    <!-- Start of Page Content -->
    <div class="page-content mb-8">
        <div class="container">
            <div class="row gutter-lg">
                <div class="main-content post-single-content">
                    <div>
                        <p class="lead">{{ __('Last modification') }}: {{ $privacyNotice->lastUpdatedToString() }}</p>
                        {!! $privacyNotice->content !!}
                    </div>
                </div>
                <!-- End of Main Content -->
            </div>
        </div>
    </div>
    <!-- End of Page Content -->
@endsection
