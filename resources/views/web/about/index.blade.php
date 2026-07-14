@extends('web.layouts.main')

@section('head')
    <title>Notaría Número 7 de Tlaquepaque - Nosotros</title>
@endsection

@section('content')
    <!--Page Header Start-->
    <section class="page-header">
        <div class="page-header__bg" style="background-image: url({{ $banner?->imagePreview() ?? '' }});">
        </div>
        <div class="container">
            <div class="page-header__inner">
                <h2>Nosotros</h2>
                <div class="thm-breadcrumb__box">
                    <ul class="thm-breadcrumb list-unstyled">
                        <li><a href="{{ route('web.home.index') }}"><i class="fas fa-home"></i>Inicio</a></li>
                        <li><span class="icon-right-arrow-1"></span></li>
                        <li class="active">Nosotros</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>
    <!--Page Header End-->

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

    <section class="sliding-text-one">
        <div class="sliding-text-one__wrap">
            <ul class="sliding-text-one__list list-unstyled marquee_mode">
                <li>
                    <h2 data-hover="Bienes Inmuebles" class="sliding-text-one__title">Bienes Inmuebles</h2>
                </li>
                <li><span></span></li>
                <li>
                    <h2 data-hover="Empresas y Sociedades" class="sliding-text-one__title">Empresas y Sociedades</h2>
                </li>
                <li><span></span></li>
                <li>
                    <h2 data-hover="Testamentos y Herencias" class="sliding-text-one__title">Testamentos y Herencias</h2>
                </li>
                <li><span></span></li>
                <li>
                    <h2 data-hover="Fideicomisos" class="sliding-text-one__title">Fideicomisos</h2>
                </li>
                <li><span></span></li>
                <li>
                    <h2 data-hover="Poderes y Mandatos" class="sliding-text-one__title">Poderes y Mandatos</h2>
                </li>
                <li><span></span></li>
                <li>
                    <h2 data-hover="Fe de Hechos" class="sliding-text-one__title">Fe de Hechos</h2>
                </li>
                <li><span></span></li>
            </ul>
        </div>
    </section>
    
    <!--Team One Start -->
    <section class="team-one">
        <div class="container">
            <div class="section-title text-left sec-title-animation animation-style2">
                <div class="section-title__tagline-box">
                    <span class="section-title__tagline">Nuestros colaboradores</span>
                    <div class="section-title__tagline-shape"></div>
                </div>
                <h2 class="section-title__title title-animation">TEAM</h2>
            </div>
            <div class="team-one__carousel owl-theme owl-carousel">
                @forelse ($team as $person)
                    <!--Team One Single Start -->
                    <div class="item">
                        <div class="team-one__single">
                            <div class="team-one__content">
                                <div class="team-one__shape-box-2">
                                    <div class="team-one__shape-2"></div>
                                </div>
                                <h3 class="team-one__name"><a href="#0">{{ $person->name }}</a></h3>
                                <p class="team-one__sub-title">{{ $person->position }}</p>
                                <div class="team-one__social">
                                    @if($person->facebook)
                                        <a href="{{ $person->facebook }}"><span class="icon-facebook-app-symbol"></span></a>
                                    @endif
                                    @if($person->twitter)
                                        <a href="{{ $person->twitter }}"><span class="icon-twitter"></span></a>
                                    @endif
                                    @if($person->linkedin)
                                        <a href="{{ $person->linkedin }}"><span class="icon-linkedin"></span></a>
                                    @endif
                                    @if($person->instagram)
                                        <a href="{{ $person->instagram }}"><span class="icon-instagram"></span></a>
                                    @endif
                                    @if($person->youtube)
                                        <a href="{{ $person->youtube }}"><span class="icon-youtube"></span></a>
                                    @endif
                                    @if($person->whatsapp)
                                        <a href="{{ $person->whatsapp }}"><span class="icon-whatsapp"></span></a>
                                    @endif
                                </div>
                            </div>
                            <div class="team-one__img-box">
                                <div class="team-one__shape-box">
                                    <div class="team-one__shape-1"></div>
                                </div>
                                <div class="team-one__img">
                                    <img src="{{ $person->imagePreview() }}" alt="">
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--Team One Single End -->
                @empty
                    <p>No hay miembros del equipo para mostrar.</p>
                @endforelse
            </div>
        </div>
    </section>
    <!--Team One End -->

    <!-- Testimonial Two Start -->
    <section class="testimonial-two">
        <div class="testimonial-two__shape-1 float-bob-y">
            <img src="{{ asset('assets/web/') }}/images/shapes/testimonial-two-shape-1.png" alt="">
        </div>
        <div class="testimonial-two__shape-2 float-bob-x">
            <img src="{{ asset('assets/web/') }}/images/shapes/testimonial-two-shape-2.png" alt="">
        </div>
        <div class="container">
            <div class="section-title text-center sec-title-animation animation-style1">
                <div class="section-title__tagline-box">
                    <span class="section-title__tagline">Testimonios</span>
                    <div class="section-title__tagline-shape"></div>
                </div>
                <h2 class="section-title__title title-animation">Que dicen nuestros clientes <br>Acerca de nuestro <span>Trabajo.</span> </h2>
            </div>
            <div class="testimonial-two__carousel owl-carousel owl-theme">
                @forelse ($testimonies as $testimony)
                    <!-- Testimonial Two Single Start -->
                    <div class="item">
                        <div class="testimonial-two__single">
                            <div class="testimonial-two__quote">
                                <span class="icon-quote"></span>
                            </div>
                            <div class="testimonial-two__img">
                                <img src="{{ $testimony->imagePreview() }}" alt="{{ $testimony->name }}">
                            </div>
                            <p class="testimonial-two__text">
                                {{ $testimony->body }}
                            </p>
                            <div class="testimonial-two__client-info">
                                <div class="testimonial-two__client-content">
                                    <h4 class="testimonial-two__client-name"><a href="#0">{{ $testimony->name }}</a>
                                    </h4>
                                    <p class="testimonial-two__client-sub-title">{{ $testimony->position }}</p>
                                </div>
                                {{-- <div class="testimonial-two__rating">
                                    <span class="icon-star"></span>
                                    <span class="icon-star"></span>
                                    <span class="icon-star"></span>
                                    <span class="icon-star"></span>
                                    <span class="icon-star"></span>
                                </div> --}}
                            </div>
                        </div>
                    </div>
                    <!-- Testimonial Two Single End -->
                @empty
                    <p>No hay testimonios para mostrar.</p>
                @endforelse
            </div>
        </div>
    </section>
    <!-- Testimonial Two End -->

    <!--Brand One Start -->
    <section class="brand-one">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-xl-3 col-lg-4 col-md-5">
                    <div class="brand-one__left">
                        <p class="brand-one__text">Conoce nuestros clientes <span>Que han confiado en nosotros</span></p>
                    </div>
                </div>
                <div class="col-xl-9 col-lg-8 col-md-7">
                    <div class="brand-one__right">
                        <div class="brand-one__carousel owl-theme owl-carousel">
                            @forelse ($partners as $partner)
                                <div class="item">
                                    <div class="brand-one__single">
                                        <div class="brand-one__img">
                                            <a href="#"><img src="{{ $partner->imagePreview() }}" alt="{{ $partner->name }}"></a>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <p>No hay clientes para mostrar.</p>  
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--Brand One End -->
@endsection