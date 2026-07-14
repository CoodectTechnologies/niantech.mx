@extends('ecommerce.layouts.main')

@section('head')
    <title>{{ __('Edit shipping address') }}</title>
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
                <li><a href="{{ route('ecommerce.account.address.index') }}">{{ __('Addresses') }}</a>
                </li>
                <li class="active">{{ __('Edit shipping address') }}</li>
            </ul>
        </div>
    </nav>
    <!-- End of Breadcrumb -->

        <!-- Start of PageContent -->
    <div  class="page-content pt-2">
        <div class="container">
            <div class="tab tab-vertical row gutter-lg">
                @include('ecommerce.account.menu.index')
                <div class="tab-content mb-6">
                    <div class="tab-pane active in" id="account-addresses">
                        <div class="row mb-5">
                            <h4 class="title title-underline ls-25 font-weight-bold">
                                {{ __('Addresses') }}
                            </h4>
                        </div>
                        @livewire('ecommerce.address.form', ['address' => $address], key($address->id))
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
