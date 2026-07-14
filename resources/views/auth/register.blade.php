@extends('auth.main')

@section('head')
    <title>{{ __('Register') }}</title>
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
                    Únete a nosotros
                </h1>  
                <div class="text-gray-600 fs-base text-center fw-semibold">
                    Crea tu cuenta y comienza a disfrutar de todos <br/>
                    los beneficios al tener tu propia cuenta
                </div>
            </div>
        </div>
        <div class="d-flex flex-column-fluid flex-lg-row-auto justify-content-center justify-content-lg-end p-12 vh-100">
            <div class="bg-body d-flex flex-column flex-center rounded-4 w-md-600px p-10 w-100">
                <div class="d-flex flex-center flex-column align-items-stretch h-lg-100 w-md-400px">
                    <div class="d-flex flex-center flex-column flex-column-fluid pb-15 pb-lg-20">
                        @include('admin.components.alert-session')
                        <form class="form w-100 mt-5" action="{{ route('register') }}" method="POST">
                            @csrf
                            <div class="text-center mb-11">
                                <h1 class="text-gray-900 fw-bolder mb-3">{{ __('Create an account') }}</h1>
                            </div>
                            <div class="fv-row mb-10">
                                <input required
                                    class="form-control form-control-md form-control-solid @error('name') is-invalid @enderror"
                                    placeholder="{{ __('Full name') }}"
                                    value="{{ old('name') }}"
                                    type="text"
                                    name="name"
                                    autocomplete="on" />
                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="fv-row mb-10">
                                <input required
                                    class="form-control form-control-md form-control-solid @error('email') is-invalid @enderror"
                                    placeholder="{{ __('Email') }}"
                                    value="{{ old('email') }}"
                                    type="email"
                                    name="email"
                                    autocomplete="on" />
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="fv-row mb-10">
                                <input required
                                    class="form-control form-control-md form-control-solid @error('password') is-invalid @enderror"
                                    placeholder="{{ __('Password') }}"
                                    value="{{ old('password') }}"
                                    type="password"
                                    name="password"
                                    autocomplete="off" />
                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="fv-row mb-10">
                                <input required
                                    class="form-control form-control-md form-control-solid @error('password_confirmation') is-invalid @enderror"
                                    placeholder="{{ __('Confirm password') }}"
                                    type="password"
                                    name="password_confirmation"
                                    autocomplete="off" />
                                @error('password_confirmation')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="fv-row mb-10">
                                <select name="country" class="form-select form-select-md form-select-solid @error('country') is-invalid @enderror">
                                    @foreach($countries as $country)
                                        <option value="{{ $country->id }}">{{ $country->name }}</option>
                                    @endforeach
                                </select>
                                @error('country')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <x-web.honey.recaptcha action="signup"/>
                            <div class="text-center">
                                <button type="submit" class="btn btn-lg btn-primary w-100 mb-5">
                                    <span class="indicator-label">{{ __('Create an account') }}</span>
                                </button>
                            </div>
                            @if(Route::has('login.google') && config('services.google.status'))
                                <div class="separator separator-content my-5">
                                    <span class="w-50px text-gray-500 fw-semibold fs-7">Ó</span>
                                </div>
                                <a href="{{ route('login.google') }}"
                                    class="btn btn-flex btn-outline btn-text-gray-700 btn-active-color-primary bg-state-light flex-center text-nowrap w-100 mb-5">
                                    <img alt="Registrate con Google"
                                        src="{{ asset('assets/admin/media/svg/brand-logos/google-icon.svg') }}"
                                        class="h-15px me-3" />
                                    {{ __('Continue with Google') }}
                                </a>
                            @endif
                            <div class="text-center">
                                <a href="{{ route('login') }}" class="link-primary fs-6">{{ __('Back to login') }}</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
