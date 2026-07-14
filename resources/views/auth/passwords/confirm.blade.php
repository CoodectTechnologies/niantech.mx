@extends('auth.main')

@section('head')
    <title>{{ __('Confirm Password') }}</title>
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
                        <form class="form w-100 mt-5" method="POST" action="{{ route('password.confirm') }}">
                            @csrf
                            <div class="text-center mb-11">
                                <h1 class="text-gray-900 fw-bolder mb-3">{{ __('Confirm Password') }}</h1>
                                <p class="text-gray-500 fw-semibold fs-6">{{ __('Please confirm your password before continuing.') }}</p>
                            </div>
                            <div class="fv-row mb-10">
                                <input
                                    id="password"
                                    type="password"
                                    class="form-control form-control-md form-control-solid @error('password') is-invalid @enderror"
                                    placeholder="{{ __('Password') }}"
                                    name="password"
                                    required
                                    autocomplete="current-password" />
                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="text-center">
                                <button type="submit" class="btn btn-lg btn-primary w-100 mb-5">
                                    <span class="indicator-label">{{ __('Confirm Password') }}</span>
                                </button>
                            </div>
                            @if(Route::has('password.request'))
                                <div class="text-center">
                                    <a href="{{ route('password.request') }}" class="link-primary fs-6">{{ __('Forgot Your Password?') }}</a>
                                </div>
                            @endif
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
