@extends('auth.main')

@section('head')
    <title>{{ __('Log in') }}</title>
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
    <div class="d-flex flex-column flex-lg-row flex-column-fluid">
        <div class="d-flex flex-lg-row-fluid">
            <div class="d-flex flex-column flex-center pb-0 pb-lg-10 p-10 w-100">   
                <img class="theme-light-show mx-auto mw-100 w-150px w-lg-300px mb-10 mb-lg-20" src="{{ asset(config('app.logo')) }}" alt=""/>    
                <img class="theme-dark-show mx-auto mw-100 w-150px w-lg-300px mb-10 mb-lg-20" src="{{ asset(config('app.logo')) }}" alt=""/>
                <h1 class="text-gray-800 fs-2qx fw-bold text-center mb-7"> 
                    Bienvenido de vuelta
                </h1>  
                <div class="text-gray-600 fs-base text-center fw-semibold">
                    Accede a tu cuenta para ver y gestionar tu información 
                </div>
            </div>
        </div>
        <div class="d-flex flex-column-fluid flex-lg-row-auto justify-content-center justify-content-lg-end p-12 vh-100">
            <div class="bg-body d-flex flex-column flex-center rounded-4 w-md-600px p-10 w-100">
                <div class="d-flex flex-center flex-column align-items-stretch h-lg-100 w-md-400px">
                    <div class="d-flex flex-center flex-column flex-column-fluid pb-15 pb-lg-20">
                        @include('admin.components.alert-session')
                        <form class="form w-100 mt-5" action="{{ route('login') }}" method="POST">
                            @csrf
                            <div class="text-center mb-11">
                                <h1 class="text-gray-900 fw-bolder mb-3">{{ __('Log in') }}</h1>
                                @if(Route::has('register'))
                                    <div class="text-gray-500 fw-semibold fs-6">{{ __("Don't have an account?") }}
                                        <a href="{{ route('register') }}"
                                            class="link-primary fw-bolder">{{ __('Create an account') }}</a>
                                    </div>
                                @endif
                            </div>
                            @php
                                $hasGoogle = Route::has('login.google') && config('services.google.status');
                            @endphp
                            @if($hasGoogle)
                            <div class="row g-3 mb-9">
                                <div class="col-lg-12">
                                    <a href="{{ route('login.google') }}" class="btn btn-flex btn-outline btn-text-gray-700 btn-active-color-primary bg-state-light flex-center text-nowrap w-100">
                                        <img alt="Logo" src="{{ asset('assets/admin/media/svg/brand-logos/google-icon.svg') }}" class="h-15px me-3"/>
                                        {{ __('Continue with Google') }}
                                    </a>
                                </div>
                            </div>
                            @endif
                            <div class="separator separator-content my-14">
                                <span class="w-300px text-gray-500 fw-semibold fs-7">{{ __('Continue with email') }}</span>
                            </div>
                            <div class="fv-row mb-10">
                                <input
                                    class="form-control form-control-md form-control-solid @error('email') is-invalid @enderror"
                                    placeholder="{{ __('Email') }}"
                                    value="{{ old('email') }}" type="email" name="email" autocomplete="on" />
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="fv-row mb-10">
                                <input
                                    class="form-control form-control-md form-control-solid @error('password') is-invalid @enderror"
                                    placeholder="{{ __('Password') }}"
                                    value="{{ old('password') }}" type="password" name="password" autocomplete="off" />
                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                                <div class="d-flex flex-end mb-2">
                                    <a href="{{ route('password.request') }}"
                                        class="link-primary fs-6">{{ __('Forgot your password?') }}</a>
                                </div>
                            </div>
                            <div class="fv-row mb-3">
                                <div class="">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="remember" id="remember"
                                            {{ old('remember') ? 'checked' : '' }}>
                                        <label class="form-check-label"  for="remember">
                                            {{ __('Remember me') }}
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="text-center">
                                <button type="submit" class="btn btn-lg btn-primary w-100 mb-5">
                                    <span class="indicator-label">{{ __('Enter') }}</span>
                                </button>
                            </div>
                        </form>
                    </div>
                    <div class=" d-flex flex-end">
                        <div class="d-flex fw-semibold text-primary fs-base gap-5">
                            @foreach($privacyNotices as $privacyNotice)
                                <a href="{{ route('web.privacy-notice.show', $privacyNotice) }}" target="_blank"><u>{{ $privacyNotice->name }}</u></a>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
