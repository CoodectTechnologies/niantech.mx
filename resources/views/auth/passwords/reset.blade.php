@extends('auth.main')

@section('head')
    <title>{{ __('Reset password') }}</title>
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
                        @if(session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif
                        @include('admin.components.alert-session')
                        <form class="form w-100 mt-5" action="{{ route('password.update') }}" method="POST">
                            @csrf
                            <input type="hidden" name="token" value="{{ $token }}">
                            <div class="text-center mb-11">
                                <h1 class="text-gray-900 fw-bolder mb-3">{{ __('Reset Password') }}</h1>
                            </div>
                            <div class="fv-row mb-10">
                                <input
                                    class="form-control form-control-md form-control-solid @error('email') is-invalid @enderror"
                                    placeholder="{{ __('Email') }}"
                                    value="{{ $email ?? old('email') }}"
                                    type="email"
                                    name="email"
                                    autocomplete="on"
                                    autofocus
                                    required />
                                @error('email')
                                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                            <div class="fv-row mb-10">
                                <input
                                    class="form-control form-control-md form-control-solid @error('password') is-invalid @enderror"
                                    placeholder="{{ __('Password') }}"
                                    id="password"
                                    type="password"
                                    name="password"
                                    required />
                                @error('password')
                                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                            <div class="fv-row mb-10">
                                <input
                                    class="form-control form-control-md form-control-solid @error('password') is-invalid @enderror"
                                    placeholder="{{ __('Confirm Password') }}"
                                    id="password-confirm"
                                    type="password"
                                    name="password_confirmation"
                                    required />
                                @error('password')
                                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                            <div class="text-center">
                                <button type="submit" class="btn btn-lg btn-primary w-100 mb-5">
                                    <span class="indicator-label">{{ __('Reset Password') }}</span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
