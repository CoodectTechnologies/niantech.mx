@extends('web.layouts.main')

@section('head')
    <title>Notaría Número 7 de Tlaquepaque - {{ $service->name }}</title>
@endsection

@section('content')
    <section class="page-header">
        <div class="page-header__bg">
        </div>
        <div class="container">
            <div class="page-header__inner">
                <h2>{{ $service->name }}</h2>
                <div class="thm-breadcrumb__box">
                    <ul class="thm-breadcrumb list-unstyled">
                        <li><a href="{{ route('web.home.index') }}"><i class="fas fa-home"></i>Inicio</a></li>
                        <li><span class="icon-right-arrow-1"></span></li>
                        <li><a href="{{ route('web.service.index') }}">Servicios</a></li>
                        <li><span class="icon-right-arrow-1"></span></li>
                        <li class="active">{{ $service->name }}</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!--Service Details Start-->
    <section class="service-details">
        <div class="container">
            <div class="row">
                <div class="col-xl-4 col-lg-5">
                    <div class="service-details__sidebar">
                        <div class="service-details__services-box">
                            <h3 class="service-details__services-title">Nuestros servicios</h3>
                            <ul class="service-details__services-list list-unstyled">
                                @foreach($services as $s)
                                    <li class="{{ $service->id === $s->id ? 'active' : '' }}">
                                        <a href="{{ route('web.service.show', $s) }}">{{ $s->name }} <span class="icon-right-arrow-1"></span></a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                        <div class="service-details__sidebar-contact text-center">
                            <div class="service-details__sidebar-contact-img">
                                <div class="inner">
                                    <img src="{{ asset('assets/web') }}/images/services/service-details-sidebar-img.png" alt="">
                                </div>
                            </div>

                            <div class="service-details__sidebar-contact-content">
                                <div class="icon">
                                    <span class="icon-call"></span>
                                </div>
                                <h2><a href="tel:{{ config('contact.phone') }}">{{ config('contact.phone') }}</a></h2>
                                <h2><a href="tel:{{ config('contact.phone2') }}">{{ config('contact.phone2') }}</a></h2>
                                <h3>Si tienes alguna duda <br>
                                    Contáctanos</h3>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-8 col-lg-7">
                    <div class="service-details__left">
                        <div class="service-details__img">
                            <img src="{{ $service->imagePreview() }}" alt="{{ $service->name }}">
                        </div>
                        <h3 class="service-details__title-1">{{ $service->name }}</h3>
                        <p class="service-details__text-1">{{ $service->fragment }}</p>
                        <p class="service-details__text-2">{!! $service->body !!}</p>
                        
                        @livewire('web.faq.index')
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--Service Details End-->
@endsection