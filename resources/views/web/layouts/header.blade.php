<header class="main-header-two">
    <nav class="main-menu main-menu-two">
        <div class="main-menu-two__wrapper">
            <div class="main-menu-two__wrapper-inner">
                <div class="main-menu-two__left">
                    <div class="main-menu-two__logo">
                        <a href="{{ route('web.home.index') }}"><img src="{{ asset(config('app.logo')) }}" width="140" alt=""></a>
                    </div>
                </div>
                <div class="main-menu-two__main-menu-box">
                    <a href="#" class="mobile-nav__toggler"><i class="fa fa-bars"></i></a>
                    <ul class="main-menu__list">
                        <li class="{{ active('web.home.index') ? 'current' : '' }}">
                            <a href="{{ route('web.home.index') }}">Notaría pública No. 7</a>
                        </li>
                        <li class="{{ active('web.about.index') ? 'current' : '' }}">
                            <a href="{{ route('web.about.index') }}">Nosotros</a>
                        </li>
                        <li class="{{ active('web.service.index') ? 'current' : '' }}">
                            <a href="{{ route('web.service.index') }}">Servicios</a>
                        </li>
                        <li class="{{ active('web.blog.index') ? 'current' : '' }}">
                            <a href="{{ route('web.blog.index') }}">Blog</a>
                        </li>
                        <li class="{{ active('web.contact.index') ? 'current' : '' }}">
                            <a href="{{ route('web.contact.index') }}">Contacto</a>
                        </li>
                    </ul>
                </div>
                <div class="main-menu-two__right">
                    <div class="main-menu-two__call">
                        <div class="main-menu-two__call-icon">
                            <i class="icon-call"></i>
                        </div>
                        <div class="main-menu-two__call-content">
                            <p class="main-menu-two__call-sub-title">llámanos</p>
                            <h5 class="main-menu-two__call-number"><a href="tel:{{ config('contact.phone') }}">{{ config('contact.phone') }}</a></h5>
                        </div>
                    </div>
                    {{-- <div class="main-menu-two__search-cart-box">
                        <div class="main-menu-two__search-cart-box">
                            <div class="main-menu-two__search-box">
                                <a href="#" class="main-menu-two__search searcher-toggler-box icon-search"></a>
                            </div>
                            <div class="main-menu-two__cart-box">
                                <a href="cart.html" class="main-menu-two__cart">
                                    <span class="icon-shopping-cart"></span>
                                    <span class="main-menu-two__cart-count">02</span>
                                </a>
                            </div>
                        </div>
                    </div> --}}
                    <div class="main-menu-two__btn-box">
                        <a href="{{ route('web.contact.index') }}" class="thm-btn">
                            <span class="thm-btn-text">Obtener una cotización</span>
                            <span class="thm-btn-icon-box"><i class="fas fa-arrow-right"></i></span>
                        </a>
                    </div>
                    <div class="main-menu-two__nav-sidebar-icon">
                        <a class="mobile-nav__toggler" href="#">
                            <span class="icon-dots-menu-one"></span>
                            <span class="icon-dots-menu-two"></span>
                            <span class="icon-dots-menu-three"></span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </nav>
</header>

<div class="stricky-header stricked-menu main-menu main-menu-two">
    <div class="sticky-header__content"></div><!-- /.sticky-header__content -->
</div><!-- /.stricky-header -->


<div class="mobile-nav__wrapper">
    <div class="mobile-nav__overlay mobile-nav__toggler"></div>
    <!-- /.mobile-nav__overlay -->
    <div class="mobile-nav__content">
        <span class="mobile-nav__close mobile-nav__toggler"><i class="fa fa-times"></i></span>

        <div class="logo-box">
            <a href="{{ route('web.home.index') }}" aria-label="logo image"><img src="{{ asset(config('app.logo')) }}" width="140"
                    alt="" /></a>
        </div>
        <!-- /.logo-box -->
        <div class="mobile-nav__container"></div>
        <!-- /.mobile-nav__container -->

        <ul class="mobile-nav__contact list-unstyled">
            <li>
                <i class="fa fa-envelope"></i>
                <a href="mailto:{{ config('contact.email') }}">{{ config('contact.email') }}</a>
            </li>
            <li>
                <i class="fas fa-phone"></i>
                <a href="tel:{{ config('contact.phone') }}">{{ config('contact.phone') }}</a>
            </li>
        </ul><!-- /.mobile-nav__contact -->
        <div class="mobile-nav__top">
            <div class="mobile-nav__social">
                @if(config('contact.twitter'))
                    <a href="{{ config('contact.twitter') }}" class="fab fa-twitter"></a>
                @endif
                @if(config('contact.facebook'))
                    <a href="{{ config('contact.facebook') }}" class="fab fa-facebook-square"></a>
                @endif
                @if(config('contact.pinterest'))
                    <a href="{{ config('contact.pinterest') }}" class="fab fa-pinterest-p"></a>
                @endif
                @if(config('contact.instagram'))
                    <a href="{{ config('contact.instagram') }}" class="fab fa-instagram"></a>
                @endif
                @if(config('contact.youtube'))
                    <a href="{{ config('contact.youtube') }}" class="fab fa-youtube"></a>
                @endif
                @if(config('contact.linkedin'))
                    <a href="{{ config('contact.linkedin') }}" class="fab fa-linkedin"></a>
                @endif
            </div><!-- /.mobile-nav__social -->
        </div><!-- /.mobile-nav__top -->
    </div>
    <!-- /.mobile-nav__content -->
</div>
<!-- /.mobile-nav__wrapper -->