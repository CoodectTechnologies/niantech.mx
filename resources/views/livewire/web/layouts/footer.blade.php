<div>
    <footer class="site-footer-two">
        {{-- <div class="site-footer-two__shape-1 float-bob-x">
            <img src="{{ asset('assets/web') }}/images/shapes/site-footer-two-shape-1.png" alt="">
        </div> --}}
        <div class="site-footer-two__shape-2 float-bob-y">
            <img src="{{ asset('assets/web') }}/images/shapes/site-footer-two-shape-2.png" alt="">
        </div>
        <div class="container">
            <div class="site-footer-two__top">
                <div class="row">
                    <div class="col-xl-3 col-lg-6 col-md-6 wow fadeInUp" data-wow-delay="100ms">
                        <div class="footer-widget-two__column footer-widget-two__about">
                            <div class="footer-widget-two__logo">
                                <a href="{{ route('web.home.index') }}"><img width="100%" src="{{ asset(config('app.logo')) }}" alt="Notaría 7"></a>
                            </div>
                            <p class="footer-widget-two__about-text">
                                Desde su fundación, esta notaría se ha distinguido por hacer las cosas bien: con responsabilidad, cumplimiento y total transparencia ante nuestra comunidad.
                            </p>
                            <div class="site-footer-two__social">
                                @if(config('contact.facebook'))
                                    <a href="{{ config('contact.facebook') }}" target="_blank"><i class="icon-facebook-app-symbol"></i></a>
                                @endif
                                @if(config('contact.linkedin'))
                                    <a href="{{ config('contact.linkedin') }}" target="_blank"><i class="icon-linkedin"></i></a>
                                @endif
                                @if(config('contact.twitter'))
                                    <a href="{{ config('contact.twitter') }}" target="_blank"><i class="icon-twitter"></i></a>
                                @endif
                                @if(config('contact.instagram'))
                                    <a href="{{ config('contact.instagram') }}" target="_blank"><i class="icon-instagram"></i></a>
                                @endif
                                @if(config('contact.youtube'))
                                    <a href="{{ config('contact.youtube') }}" target="_blank"><i class="icon-youtube"></i></a>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-6 col-md-6 wow fadeInUp" data-wow-delay="200ms">
                        <div class="footer-widget-two__column footer-widget-two__usefull-link">
                            <div class="footer-widget-two__title-box">
                                <h4 class="footer-widget-two__title"><u>Menu</u></h4>
                            </div>
                            <div class="footer-widget-two__link-box">
                                <ul class="footer-widget-two__link list-unstyled">
                                    <li><a href="{{ route('web.home.index') }}">Inicio</a></li>
                                    <li><a href="{{ route('web.about.index') }}">Nosotros</a></li>
                                    <li><a href="{{ route('web.service.index') }}">Servicios</a></li>
                                    <li><a href="{{ route('web.blog.index') }}">Blog</a></li>
                                    <li><a href="{{ route('web.contact.index') }}">Contacto</a></li>
                                </ul>
                                <ul class="footer-widget-two__link footer-widget-two__link-2 list-unstyled">
                                    @foreach($privacyNotices as $privacyNotice)
                                        <li><a href="{{ route('web.privacy-notice.show', $privacyNotice) }}"><u>{{ $privacyNotice->name }}</u></a></li>        
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-2 col-lg-6 col-md-6 wow fadeInUp" data-wow-delay="300ms">
                        <div class="footer-widget-two__column footer-widget-two__services">
                            <div class="footer-widget-two__title-box">
                                <h4 class="footer-widget-two__title">Servicios</h4>
                            </div>
                            <ul class="footer-widget-two__link list-unstyled">
                                @foreach($services as $service)
                                    <li><a href="{{ route('web.service.show', $service) }}">{{ $service->name }}</a></li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-6 col-md-6 wow fadeInUp" data-wow-delay="400ms">
                        <div class="footer-widget-two__column footer-widget-two__services">
                            <div class="footer-widget-two__title-box">
                                <h4 class="footer-widget-two__title">Blog</h4>
                            </div>
                            <div class="footer-widget-two__title-box">
                                <ul class="footer-widget-two__link list-unstyled">
                                    @foreach($posts as $post)
                                        <li><a href="{{ route('web.blog.show', $post) }}">{{ $post->name }}</a></li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="site-footer-two__bottom">
            <div class="container">
                <div class="site-footer-two__bottom-inner">
                    <p class="site-footer-two__bottom-text">© <u>Copyright</u> {{ date('Y') }} <a href="#">Notaría Número 7 de Tlaquepaque</a>. <u>Todos los derechos reservados.</u></p>
                    <ul class="list-unstyled site-footer-two__bottom-menu">
                        <li><a href="#0"><u>Desarrollado por: </u></a></li>
                        <li><a href="https://inventamarketing.com.mx"><u>Inventa Marketing</u></a></li>
                    </ul>
                </div>
            </div>
        </div>
    </footer>
</div>
