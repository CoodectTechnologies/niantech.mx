@extends('auth.main')

@section('head')
    <title>{{ __('Verify Email Address') }}</title>
    <style>
        body {
            background-image: url('{{ asset("assets/admin/media/auth/bg10.jpeg") }}');
        }
        [data-bs-theme="dark"] body {
            background-image: url('{{ asset("assets/admin/media/auth/bg10-dark.jpeg") }}');
        }
    </style>
    <!--end::Page bg image-->
@endsection

@section('content')
    <div class="d-flex flex-column flex-center flex-column-fluid">
        <div class="d-flex flex-center p-10 pb-lg-20">
            <div class="bg-body d-flex flex-column flex-center rounded-4 w-md-600px p-10">
                <div class="d-flex flex-center flex-column align-items-stretch h-lg-100 w-md-400px">
                    <div class="d-flex flex-center flex-column flex-column-fluid pb-15 pb-lg-20">
                        <div class="text-center mb-10">
                            @if(Route::has('ecommerce.home.index'))
                                <a href="{{ route('ecommerce.home.index') }}">
                                    <img class="img-fluid" src="{{ asset(config('app.logo')) }}" alt="{{ config('app.name') }}">
                                </a>
                            @elseif(Route::has('elearning.home.index'))
                                <a href="{{ route('elearning.home.index') }}">
                                    <img class="img-fluid" src="{{ asset(config('app.logo')) }}" alt="{{ config('app.name') }}">
                                </a>
                            @elseif(Route::has('web.home.index'))
                                <a href="{{ route('web.home.index') }}">
                                    <img class="img-fluid" src="{{ asset(config('app.logo')) }}" alt="{{ config('app.name') }}">
                                </a>
                            @endif
                        </div>
                        <div class="w-100 mt-5">
                            <div class="text-center mb-11">
                                <h1 class="text-gray-900 fw-bolder mb-3">{{ __('Verify Your Email Address') }}</h1>
                            </div>
                            @if(session('resent'))
                                <div class="alert alert-success" role="alert">
                                    {{ __('A fresh verification link has been sent to your email address.') }}
                                </div>
                            @endif
                            <p class="text-gray-600 fw-semibold fs-6 mb-8">
                                {{ __('Before proceeding, please check your email for a verification link.') }}
                            </p>
                            <p class="text-gray-600 fw-semibold fs-6 mb-8">{{ __('If you did not receive the email') }},</p>
                            <form method="POST" action="{{ route('verification.resend') }}">
                                @csrf
                                <div class="text-center">
                                    <button type="submit" class="btn btn-lg btn-primary w-100 mb-5">
                                        <span class="indicator-label">{{ __('click here to request another') }}</span>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
