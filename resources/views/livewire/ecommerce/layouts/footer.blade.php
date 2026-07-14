<div class="container">
    <div class="footer-top">
        <div class="row">
            {{-- Logo y descripción --}}
            <div class="col-lg-3 mb-5">
                <div class="widget widget-about">
                    <a href="{{ route('ecommerce.home.index') }}" class="logo-footer">
                        @if(session()->get('theme-type') == 'dark')
                            <img loading="lazy"
                                src="{{ asset(config('app.logo_white')) }}"
                                alt="{{ config('app.name') }}"
                                width="180">
                        @else
                            <img loading="lazy"
                                src="{{ asset(config('app.logo')) }}"
                                alt="{{ config('app.name') }}"
                                width="180">
                        @endif
                    </a>

                    <div class="widget-body mt-4">
                        <p>
                            Soluciones tecnológicas innovadoras en hardware,
                            software y accesorios para empresas y hogares.
                        </p>

                        <div class="social-icons mt-4">

                            @if(config('contact.facebook'))
                                <a href="{{ config('contact.facebook') }}"
                                target="_blank"
                                class="social-icon">
                                    <i class="fa-brands fa-facebook-f"></i>
                                </a>
                            @endif

                            @if(config('contact.instagram'))
                                <a href="{{ config('contact.instagram') }}"
                                target="_blank"
                                class="social-icon">
                                    <i class="fa-brands fa-instagram"></i>
                                </a>
                            @endif

                            @if(config('contact.linkedin'))
                                <a href="{{ config('contact.linkedin') }}"
                                target="_blank"
                                class="social-icon">
                                    <i class="fa-brands fa-linkedin-in"></i>
                                </a>
                            @endif

                            @if(config('contact.youtube'))
                                <a href="{{ config('contact.youtube') }}"
                                target="_blank"
                                class="social-icon">
                                    <i class="fa-brands fa-youtube"></i>
                                </a>
                            @endif

                        </div>
                    </div>
                </div>
            </div>

            {{-- Enlaces --}}
            <div class="col-lg-2 mb-5">
                <div class="widget">
                    <h4 class="widget-title">Enlaces</h4>

                    <ul class="widget-body">
                        <li><a href="{{ route('ecommerce.product.index') }}">Productos</a></li>
                        <li><a href="{{ route('ecommerce.about.index') }}">Quienes somos</a></li>
                        <li><a href="{{ route('ecommerce.category.index') }}">Categorias</a></li>
                        <li><a href="{{ route('ecommerce.blog.index') }}">Blog</a></li>
                        <li><a href="{{ route('ecommerce.contact.index') }}">Contacto</a></li>
                        <li><a href="{{ route('ecommerce.about.index') }}">Nosotros</a></li>
                    </ul>
                </div>
            </div>

            {{-- Categorías --}}
            <div class="col-lg-2 mb-5">
                <div class="widget">
                    <h4 class="widget-title">Categorías</h4>

                    <ul class="widget-body">
                        @foreach ($categoriesFhater as $categoryFhater)
                            <li><a href="{{ route('ecommerce.product.index', ['category' => $categoryFhater->slug]) }}">{{ $categoryFhater->name }}</a></li>
                        @endforeach
                    </ul>
                </div>
            </div>

            {{-- Ayuda --}}
            <div class="col-lg-2 mb-5">
                <div class="widget">
                    <h4 class="widget-title">Ayuda</h4>

                    <ul class="widget-body">
                        <li><a href="#">Envíos y entregas</a></li>
                        <li><a href="#">Devoluciones</a></li>
                        <li><a href="#">Métodos de pago</a></li>
                        <li><a href="#">Garantías</a></li>
                        <li><a href="#">Preguntas frecuentes</a></li>
                    </ul>
                </div>
            </div>

            {{-- Contacto --}}
            <div class="col-lg-3 mb-5">
                <div class="widget">
                    <h4 class="widget-title">Contacto</h4>

                    <ul class="widget-body list-unstyled">

                        @if (config('contact.phone'))
                            <li class="mb-3">
                                <i class="fa-light fa-phone text-primary me-2"></i>
                                {{ config('contact.phone') }}
                            </li>
                        @endif 

                        @if (config('contact.email'))
                            <li class="mb-3">
                                <i class="fa-light fa-envelope text-primary me-2"></i>
                                {{ config('contact.email') }}
                            </li>
                        @endif

                        @if (config('contact.address'))
                            <li>
                                <i class="fa-light fa-location-dot text-primary me-2"></i>
                                {{ config('contact.address') }}
                            </li>
                        @endif

                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="footer-bottom border-top">
        <div class="container d-flex justify-content-between align-items-center flex-wrap py-3">

            <p class="copyright mb-0">
                © {{ date('Y') }} {{ config('app.name') }}.
                Todos los derechos reservados.
            </p>

            <div>
                @foreach($privacyNotices as $privacyNotice)
                    <a href="{{ route('ecommerce.privacy-notice.show', $privacyNotice) }}"
                    class="mx-2">
                        {{ $privacyNotice->name }}
                    </a>
                @endforeach
            </div>

        </div>
    </div>
</div>
