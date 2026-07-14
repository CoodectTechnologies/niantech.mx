@extends('web.layouts.main')

@section('head')
    <title>Notaría Número 7 de Tlaquepaque - Contacto</title>
@endsection

@section('content')
    <section class="page-header">
        <div class="page-header__bg" style="background-image: url({{ $banner?->imagePreview() ?? '' }});">
        </div>
        <div class="container">
            <div class="page-header__inner">
                <h2>Contacto</h2>
                <div class="thm-breadcrumb__box">
                    <ul class="thm-breadcrumb list-unstyled">
                        <li><a href="{{ route('web.home.index') }}"><i class="fas fa-home"></i>Inicio</a></li>
                        <li><span class="icon-right-arrow-1"></span></li>
                        <li class="active"><a>Contacto</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!--Contact Info Start-->
    <section class="contact-info">
        <div class="container">
            <div class="row">
                <!--Contact Two Single Start-->
                <div class="col-xl-4 col-lg-4">
                    <div class="contact-info__single">
                        <div class="contact-info__icon">
                            <span class="icon-call"></span>
                        </div>
                        <p>Contáctenos</p>
                        <h3><a href="tel:{{ config('contact.phone') }}">{{ config('contact.phone') }}</a></h3>
                    </div>
                </div>
                <!--Contact Two Single End-->
                <!--Contact Two Single Start-->
                <div class="col-xl-4 col-lg-4">
                    <div class="contact-info__single">
                        <div class="contact-info__icon">
                            <span class="icon-email"></span>
                        </div>
                        <p>Correo Electrónico</p>
                        <h3><a href="mailto:{{ config('contact.email') }}">{{ config('contact.email') }}</a></h3>
                    </div>
                </div>
                <!--Contact Two Single End-->
                <!--Contact Two Single Start-->
                <div class="col-xl-4 col-lg-4">
                    <div class="contact-info__single">
                        <div class="contact-info__icon">
                            <span class="icon-pin"></span>
                        </div>
                        <p>Nuestra Ubicación</p>
                        <h3>Del Parque #480, Chapalita Oriente, 45040 Zapopan, Jalisco.</h3>
                    </div>
                </div>
                <!--Contact Two Single End-->
            </div>
        </div>
    </section>
    <!--Contact Info End-->

    <!--Contact Page Start-->
    <section class="contact-page">
        <div class="container">
            <div class="contact-page__inner">
                <div class="row">
                    <div class="col-xl-6">
                        <div class="contact-page__left">
                            {!! config('contact.map') !!}
                        </div>
                    </div>
                    <div class="col-xl-6">
                        <div class="contact-page__right">
                            <h3 class="contact-page__form-title">Contáctenos</h3>
                            @livewire('web.contact.index')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--Contact Page End-->
@endsection