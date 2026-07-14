<nav class="main-nav">
    <ul class="menu">
        @if(Route::has('ecommerce.product.index'))
            <li class="{{ active('ecommerce.product.index') }}">
                <a href="{{ route('ecommerce.product.index') }}">{{ __('Products') }}</a>
            </li>
        @endif
        @if(Route::has('ecommerce.about.index'))
            <li class="{{ active('ecommerce.about.index') }}">
                <a class="menu-border-right" href="{{ route('ecommerce.about.index') }}">{{ __('About') }}</a>
            </li>
        @endif
        @if(Route::has('ecommerce.blog.index'))
            <li class="{{ active('ecommerce.blog.index') }}">
                <a class="menu-border-right" href="{{ route('ecommerce.blog.index') }}">{{ __('Blog') }}</a>
            </li>
        @endif
        @if(Route::has('ecommerce.gallery.index'))
            <li class="{{ active('ecommerce.gallery.index') }}">
                <a class="menu-border-right" href="{{ route('ecommerce.gallery.index') }}">{{ __('Gallery') }}</a>
            </li>
        @endif
        @if(Route::has('ecommerce.contact.index'))
            <li class="{{ active('ecommerce.contact.index') }}">
                <a class="menu-border-right" href="{{ route('ecommerce.contact.index') }}">{{ __('Contact') }}</a>
            </li>
        @endif
    </ul>
</nav>
