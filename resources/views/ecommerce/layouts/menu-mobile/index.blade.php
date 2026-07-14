<!-- Start of Mobile Menu -->
<div class="mobile-menu-wrapper">
    <div class="mobile-menu-overlay"></div>
    <!-- End of .mobile-menu-overlay -->

    <a href="#" class="mobile-menu-close"><i class="close-icon"></i></a>
    <!-- End of .mobile-menu-close -->

    <div class="mobile-menu-container scrollable">
        <form action="{{ route('ecommerce.product.index') }}" method="get" class="input-wrapper d-lg-none">
            <input type="text" class="form-control" name="search" autocomplete="off"
                placeholder="{{ __('Search') }}" required />
            <button class="btn btn-search" type="submit">
                <i class="w-icon-search"></i>
            </button>
        </form>
        <!-- End of Search Form -->
        <div>
            <div id="main-menu" class="d-lg-none d-block">
                <ul class="mobile-menu">
                    @if(Route::has('ecommerce.blog.index'))
                        <li class="{{ active('ecommerce.home.index') }}">
                            <a href="{{ route('ecommerce.home.index') }}">{{ __('Home') }}</a>
                        </li>
                    @endif
                    @if(Route::has('ecommerce.product.index'))
                        <li class="{{ active('ecommerce.product.index') }}">
                            <a href="{{ route('ecommerce.product.index') }}">{{ __('Products') }}</a>
                        </li>
                    @endif
                    @if(Route::has('ecommerce.configurator.index') && config('configurator.active'))
                        <li class="{{ active('ecommerce.configurator.index') }}">
                            <a href="{{ route('ecommerce.configurator.index') }}">{{ __('Build your pc') }}</a>
                        </li>
                    @endif
                    @if(Route::has('ecommerce.about.index'))
                        <li class="{{ active('ecommerce.about.index') }}">
                            <a href="{{ route('ecommerce.about.index') }}">{{ __('About') }}</a>
                        </li>
                    @endif
                    @if(Route::has('ecommerce.blog.index'))
                        <li class="{{ active('ecommerce.blog.index') }}">
                            <a href="{{ route('ecommerce.blog.index') }}">{{ __('Blog') }}</a>
                        </li>
                    @endif
                    @if(Route::has('ecommerce.gallery.index'))
                        <li class="{{ active('ecommerce.gallery.index') }}">
                            <a href="{{ route('ecommerce.gallery.index') }}">{{ __('Gallery') }}</a>
                        </li>
                    @endif
                    @if(Route::has('ecommerce.contact.index'))
                        <li class="{{ active('ecommerce.contact.index') }}">
                            <a href="{{ route('ecommerce.contact.index') }}">{{ __('Contact') }}</a>
                        </li>
                    @endif
                    @if(Route::has('ecommerce.account.dashboard.index'))
                        <li class="{{ active('ecommerce.account.dashboard.index') }}">
                            <a href="{{ route('ecommerce.account.dashboard.index') }}">{{ __('Profile') }}</a>
                        </li>
                    @endif
                </ul>
            </div>
            <div id="categories">
                <h4 class="subtitle text-primary">{{ __('Categories') }}</h4>
                @include('ecommerce.layouts.menu-mobile.category')
            </div>
        </div>
    </div>
</div>
<!-- End of Mobile Menu -->
