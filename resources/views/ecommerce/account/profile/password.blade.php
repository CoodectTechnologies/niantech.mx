@extends('ecommerce.layouts.main')

@section('head')
    <title>{{ __('Password') }}</title>
    <meta name="title" content="{{ config('app.name') }}" />
    <meta name="description" content="{{ config('app.name') }}" />
@endsection

@section('body-class')
    class="my-account"
@endsection

@section('content')
    <!-- Start of Breadcrumb -->
    <nav class="breadcrumb-nav">
        <div class="container">
            <ul class="breadcrumb">
                <li><a href="{{ route('ecommerce.home.index') }}">{{ __('Home') }}</a></li>
                <li><a href="{{ route('ecommerce.account.dashboard.index') }}">{{ __('My account') }}</a></li>
                <li class="active">{{ __('Password') }}</li>
            </ul>
        </div>
    </nav>
    <!-- End of Breadcrumb -->

    @livewire('ecommerce.account.profile.password')
@endsection
