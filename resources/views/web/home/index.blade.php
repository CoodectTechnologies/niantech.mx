@extends('web.layouts.main')

@section('head')
    <title>Notaría Número 7 de Tlaquepaque</title>
@endsection

@section('content')
    <section class="main-slider-two">
        <div class="main-slider-two__carousel owl-carousel owl-theme">
            @foreach($banners as $banner)
                <div class="item">
                    <div class="main-slider-two__bg"
                        style="background-image: url({{ $banner->imagePreview() }});">
                    </div>
                    <div class="main-slider-two__overly"></div>
                    <div class="container">
                        <div class="main-slider-two__content">
                            {{-- <div class="main-slider-two__sub-title-box">
                                <div class="main-slider-two__sub-title-shape"></div>
                                <p class="main-slider-two__sub-title">{{ $banner->subtitle }}</p>
                            </div> --}}
                            <h2 class="main-slider-two__title" style="color: {{ $banner->color }};">{!! $banner->title !!}</h2>
                            <p class="main-slider-two__text" style="color: {{ $banner->color }};">{!! $banner->subtitle !!}</p>
                            @if($banner->btn_text)
                                <div class="main-slider-two__btn-box">
                                    <div class="main-slider-two__btn">
                                        <a href="{{ $banner->btn_url }}" class="thm-btn">
                                            <span class="thm-btn-text" style="color: {{ $banner->color }};"><u>{{ $banner->btn_text }}</u></span>
                                            <span class="thm-btn-icon-box"><i class="fas fa-arrow-right"></i></span>
                                        </a>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    <section class="about-two">
        <div class="container">
            <div class="row">
                <div class="col-xl-6">
                    <div class="about-two__left wow slideInLeft" data-wow-delay="100ms" data-wow-duration="2500ms">
                        <div class="about-two__img-box">
                            <div class="about-two__img">
                                <img src="{{ $about->imagePreview() }}" alt="">
                            </div>
                            <div class="about-two__img-two">
                                <img src="{{ $about->image2Preview() }}" alt="">
                            </div>
                            <div class="about-two__img-content">
                                <div class="text-box">
                                    <h4><u>Excelencia</u><br>Jurídica</h4>
                                </div>
                            </div>
                            <div class="about-two__shape-1"></div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-6">
                    <div class="about-two__right">
                        <div class="section-title text-left sec-title-animation animation-style2">
                            <div class="section-title__tagline-box">
                                <span class="section-title__tagline">NOSOTROS</span>
                                <div class="section-title__tagline-shape"></div>
                            </div>
                            <h2 class="section-title__title title-animation">{!! $about->title !!}</h2>
                        </div>
                        <p class="about-two__text">
                            {!! $about->information !!}
                        </p>
                        
                        <div class="about-two__points-and-vission-mission">
                            <div class="about-two__points-box">
                                <ul class="about-two__points list-unstyled">
                                    @foreach(explode(',', $about->values) as $value)
                                        <li><p>{{ $value }}</p></li>
                                    @endforeach
                                </ul>
                            </div>
                            <div class="about-two__vission-mission">
                                <div class="about-two__tab-box tabs-box">
                                    <ul class="tab-buttons clearfix list-unstyled">
                                        <li data-tab="#vission" class="tab-btn active-btn"><span>Misión</span></li>
                                        <li data-tab="#mission" class="tab-btn"><span>Visión</span></li>
                                    </ul>
                                    <div class="tabs-content">
                                        <div class="tab active-tab" id="vission">
                                            <div class="tabs-content__inner">
                                                <p>{!! $about->mission !!}</p>
                                            </div>
                                        </div>
                                        <div class="tab" id="mission">
                                            <div class="tabs-content__inner">
                                                <p>{!! $about->vision !!}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="about-two__bottom">
                            <div class="about-two__founder">
                                <div class="about-two__founder-text-box" style="padding-left: 0;">
                                    <h4>Lic. Salvador Guillermo Plaza Arana</h4>
                                    <p>Notario Público</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="services-two">
        <div class="container">
            <div class="section-title text-center sec-title-animation animation-style1">
                <div class="section-title__tagline-box">
                    <span class="section-title__tagline">SERVICIOS</span>
                    <div class="section-title__tagline-shape"></div>
                </div>
                <h2 class="section-title__title title-animation">Certeza jurídica en cada etapa <br> <span>patrimonial y empresarial</span></h2>
            </div>
            <div class="row">
                @foreach($services as $service)
                    <div class="col-xl-4 col-lg-6 col-md-6 wow fadeInRight">
                        <div class="services-two__single">
                            <div class="services-two__icon"><span class="icon-shield"></span></div>
                            <h3 class="services-two__title"><a href="{{ route('web.service.show', $service) }}">{{ $service->title }}</a></h3>
                            <p class="services-two__text">{{ $service->fragment }}</p>
                            <a href="#" class="services-two__read-more"><u>Conocer más</u> <span class="icon-right-arrow"></span></a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <section class="why-choose-two">
        <div class="container">
            <div class="row">
                <div class="col-xl-6">
                    <div class="why-choose-two__right">
                        <div class="section-title text-left sec-title-animation animation-style2">
                            <div class="section-title__tagline-box">
                                <span class="section-title__tagline">¿POR QUÉ ELEGIRNOS?</span>
                                <div class="section-title__tagline-shape"></div>
                            </div>
                            <h2 class="section-title__title title-animation">Nuestra vocación <span>trasciende</span></h2>
                        </div>
                        <p class="why-choose-two__text">Buscamos que esta notaría trascienda como una institución sólida, ofreciendo continuidad, calidad y confianza para las próximas generaciones.</p>
                        <div class="why-choose-two__points-box">
                            <ul class="list-unstyled why-choose-two__points">
                                <li>
                                    <div class="icon"><span class="icon-lawyer"></span></div>
                                    <div class="content">
                                        <h3><u>Experiencia</u></h3>
                                        <p>Acompañamiento humano en <br> momentos clave.</p>
                                    </div>
                                </li>
                                <li>
                                    <div class="icon"><span class="icon-umbrella"></span></div>
                                    <div class="content">
                                        <h3><u>Confianza</u></h3>
                                        <p>Absoluto rigor jurídico en <br> cada documento.</p>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-xl-6">
                    <div class="why-choose-two__left wow slideInRight">
                        <div class="why-choose-two__img">
                            <img src="{{ asset('assets/web') }}/images/resources/why-choose-two-img-1.jpg" alt="">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="contact-two">
        <div class="container">
            <div class="contact-two__inner">
                <div class="row">
                    <div class="col-xl-7">
                        <div class="section-title text-left">
                            <div class="section-title__tagline-box">
                                <span class="section-title__tagline">CONTACTO</span>
                                <div class="section-title__tagline-shape"></div>
                            </div>
                            <h2 class="section-title__title">¿Tienes alguna duda? <span>Contáctanos.</span></h2>
                        </div>
                        @livewire('web.contact.index')
                    </div>
                    <div class="col-xl-5">
                        <div class="contact-two__right">
                            <div class="contact-two__info-box">
                                <h3 class="contact-two__info-title">Datos de Contacto</h3>
                                <ul class="contact-two__info-list list-unstyled">
                                    <li>
                                        <div class="icon"><span class="icon-pin"></span></div>
                                        <div class="content"><p>Del Parque #480, Chapalita Oriente, 45040 Zapopan, Jalisco.</p></div>
                                    </li>
                                    <li>
                                        <div class="icon"><span class="icon-call"></span></div>
                                        <div class="content">
                                            <p><a href="tel:{{ config('contact.phone') }}">{{ config('contact.phone') }}</a></p>
                                            <p><a href="tel:{{ config('contact.phone2') }}">{{ config('contact.phone2') }}</a></p>
                                        </div>
                                    </li>
                                    @if(config('contact.instagram'))
                                        <li>
                                            <div class="icon"><i class="fab fa-instagram"></i></div>
                                            <div class="content"><p>{{ config('contact.instagram') }}</p></div>
                                        </li>
                                        @endif
                                    @if(config('contact.facebook'))
                                        <li>
                                            <div class="icon"><i class="fab fa-facebook"></i></div>
                                            <div class="content"><p>{{ config('contact.facebook') }}</p></div>
                                        </li>
                                    @endif
                                    @if(config('contact.twitter'))
                                        <li>
                                            <div class="icon"><i class="fab fa-twitter"></i></div>
                                            <div class="content"><p>{{ config('contact.twitter') }}</p></div>
                                        </li>
                                    @endif
                                    @if(config('contact.youtube'))
                                        <li>
                                            <div class="icon"><i class="fab fa-youtube"></i></div>
                                            <div class="content"><p>{{ config('contact.youtube') }}</p></div>
                                        </li>
                                    @endif
                                    @if(config('contact.linkedin'))
                                        <li>
                                            <div class="icon"><i class="fab fa-linkedin"></i></div>
                                            <div class="content"><p>{{ config('contact.linkedin') }}</p></div>
                                        </li>
                                    @endif
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="blog-two">
        <div class="container">
            <div class="section-title text-left sec-title-animation animation-style2">
                <div class="section-title__tagline-box justify-content-center">
                    <div class="section-title__tagline-shape"></div>
                    <span class="section-title__tagline">Blog</span>
                    <div class="section-title__tagline-shape"></div>
                </div>
                @if(empty($posts))
                    <h2 class="section-title__title">Próximamente encontrarás <span>información y artículos</span></h2>
                    <p>Guías prácticas sobre trámites notariales y temas de interés.</p>
                @else
                    <h2 class="section-title__title title-animation">Nuestros últimas <span>publicaciones</span></h2>
                @endif
            </div>
            <div class="blog-two__carousel owl-carousel owl-theme">
                @foreach($posts as $post)
                    <!--Blog Two Single Start -->
                    <div class="item">
                        <div class="blog-two__single">
                            <div class="blog-two__img-box">
                                <div class="blog-two__img">
                                    <img src="{{ $post->imagePreview() }}" alt="{{ $post->name }}">
                                    @foreach($post->blogTags as $tag)
                                        <div class="blog-two__tags">
                                            <span>{{ $tag->name }}</span>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="blog-two__date">
                                    <p>{{ \Carbon\Carbon::parse($post->created_at)->format('d') }}</p>
                                    <span>{{ \Carbon\Carbon::parse($post->created_at)->format('M') }}</span>
                                </div>
                            </div>
                            <div class="blog-two__content">
                                <div class="blog-two__user">
                                    <div class="blog-two__user-img">
                                        <img src="{{ $post->user && $post->user->image ? $post->user->image : asset('assets/images/blog/blog-two-user-1-1.jpg') }}" alt="{{ $post->user->name ?? 'Autor' }}">
                                    </div>
                                    <div class="blog-two__user-content">
                                        <h5 class="blog-two__user-name">
                                            <a href="#">{{ $post->user->name ?? 'Autor' }}</a>
                                        </h5>
                                        <p class="blog-two__sub-title">
                                            @if($post->blogCategories->isNotEmpty())
                                                {{ $post->blogCategories->first()->name }}
                                            @endif
                                        </p>
                                    </div>
                                </div>
                                <h3 class="blog-two__title">
                                    <a href="{{ route('web.blog.show', $post) }}">{{ $post->name }}</a>
                                </h3>
                                <p class="blog-two__text">{{ $post->fragment }}</p>
                                <a href="{{ route('web.blog.show', $post) }}" class="blog-two__read-more">Leer más <span class="fas fa-arrow-right"></span></a>
                            </div>
                        </div>
                    </div>
                    <!--Blog Two Single End -->
                @endforeach
            </div>
        </div>
    </section>
@endsection