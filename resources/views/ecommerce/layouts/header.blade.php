<!-- Start of Header -->
<header class="header">
    @include('ecommerce.components.alert-impersonate')
    <div class="d-none header-top">
        <div class="container">
            <div class="header-left">
                @if(!session()->has('impersonated_by'))
                    <p class="welcome-msg me-5">¡{{ __('Welcome to') }} {{ config('app.name') }}!</p>
                @endif
                {{-- @include('ecommerce.components.alert-impersonate') --}}
            </div>
            <div class="header-right pr-0">
                @if(count(currencies()) > 1)
                    <div class="dropdown">
                        <a href="#currency"><span class="text-uppercase">{{ session()->get('currency') }}</span></a>
                        <div wire:ignore.self class="dropdown-box">
                            @foreach (currencies() as $currency)
                                <a href="{{ route('ecommerce.currency', $currency->code) }}">{{ $currency->code }}</a>
                            @endforeach
                        </div>
                    </div>
                @endif
                @if(count(languages()) > 1 && language())
                    <!-- End of DropDown Menu -->
                    <div class="dropdown">
                        <a href="#language">
                            <img loading="lazy" src="{{ languages()[language()]['flag'] }}" alt="{{ language() }}"
                                width="14" height="8" class="dropdown-image" />
                            <span class="text-uppercase">{{ language() }}</span>
                        </a>
                        <div class="dropdown-box">
                            @foreach (languages() as $locale => $language)
                                <a href="{{ route('ecommerce.language', ['language' => $locale]) }}">
                                    <img loading="lazy" src="{{ $language['flag'] }}" alt="{{ $language['name'] }}"
                                        width="14" height="8" class="dropdown-image" />
                                    {{ Str::upper($locale) }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                    <!-- End of Dropdown Menu -->
                @endif
                <span class="divider d-lg-show"></span>
                {{-- <a href="blog.html" class="d-lg-show">{{ __('Blog') }}</a> --}}
                <a href="{{ route('ecommerce.contact.index') }}" class="d-lg-show">{{ __('Contact us') }}</a>
                @auth
                    <a href="{{ route('ecommerce.account.dashboard.index') }}"
                        class="d-lg-show">{{ __('My account') }}</a>
                    <span class="delimiter d-lg-show">/</span>
                    <a onclick="event.preventDefault(); document.getElementById('logout-form').submit();" href="#"
                        class="ml-0 d-lg-show login">{{ __('Logout') }}</a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                @endauth
                @guest
                    <a href="{{ route('login') }}" class="d-lg-show login sign-in">
                        <i class="w-icon-account"></i>{{ __('Sign in') }}
                    </a>
                    <span class="delimiter d-lg-show">/</span>
                    <a href="{{ route('register') }}" class="ml-0 d-lg-show login register">{{ __('Register') }}</a>
                @endguest
            </div>
        </div>
    </div>
    <!-- End of Header Top -->
    <div class="header-middle">
        <div class="container">
            <div class="header-left mr-md-4">
                <a href="#" class="mobile-menu-toggle  w-icon-hamburger d-none"></a>
                <a href="{{ route('ecommerce.home.index') }}" class="logo ml-lg-0">
                    <img loading="lazy" src="{{ asset(config('app.logo')) }}" alt="logo" width="180" />
                </a>
                @livewire('ecommerce.layouts.search')
            </div>
            <div class="header-right ms-4">
                <div class="social-icons social-icons-colored">
                    @if (config('contact.facebook'))
                        <a href="{{ config('contact.facebook') }}" target="_blank" class="social-icon social-facebook">
                            <i class="fa-brands fa-facebook-f"></i>
                        </a>
                    @endif
                    @if(config('contact.instagram'))
                        <a href="{{ config('contact.instagram') }}" target="_blank" class="social-icon social-instagram">
                            <i class="fa-brands fa-instagram"></i>
                        </a>
                    @endif
                    @if(config('contact.youtube'))
                        <a href="{{ config('contact.youtube') }}" target="_blank" class="social-icon social-youtube">
                            <i class="fa-brands fa-youtube"></i>
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <!-- End of Header Middle -->
    <div class="header-bottom sticky-content fix-top sticky-header border-bottom "> <!-- has-dropdown -->
        <div class="container">
            <div class="inner-wrap">
                <div class="header-left flex-1">
                    <div class="dropdown category-dropdown has-border @yield('category-dropdown')" data-visible="true">
                        {{-- <a href="#" class="mobile-menu-toggle">
                            <i class="w-icon-category"></i>
                            <span>{{ __('Categories') }}</span>
                        </a> --}}
                        <a href="#" class="mobile-menu-toggle" role="button">
                            <i class="w-icon-category"></i>
                            <span class="d-none d-lg-block">{{ __('Categories') }}</span>
                            <span class="d-lg-none d-block">{{ __('Menu') }}</span>
                        </a>
                        {{-- @include('ecommerce.layouts.menu.category') --}}
                    </div>
                    @include('ecommerce.layouts.menu.index')
                </div>
                <div class="header-right">
                    <div class="dropdown cart-dropdown me-3">
                        <a href="{{ route('ecommerce.account.dashboard.index') }}" class="cart-toggle label-down link">
                            <i class="fa-notdog fa-solid fa-user fa-lg"></i>
                            {{-- <span class="cart-label">{{ __('Account') }}</span> --}}
                        </a>
                    </div>
                    @livewire('ecommerce.layouts.wishlist')
                    @livewire('ecommerce.layouts.compare')
                    @livewire('ecommerce.layouts.cart')
                </div>
            </div>
        </div>
    </div>
</header>
<!-- End of Header -->
