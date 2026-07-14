@extends('web.layouts.main')

@section('head')
    <title>Notaría Número 7 de Tlaquepaque - Servicios</title>
@endsection

@section('content')
    <section class="page-header">
        <div class="page-header__bg" style="background-image: url({{ $banner?->imagePreview() ?? '' }});">
        </div>
        <div class="container">
            <div class="page-header__inner">
                <h2>Servicios</h2>
                <div class="thm-breadcrumb__box">
                    <ul class="thm-breadcrumb list-unstyled">
                        <li><a href="{{ route('web.home.index') }}"><i class="fas fa-home"></i>Inicio</a></li>
                        <li><span class="icon-right-arrow-1"></span></li>
                        <li class="active"><a>Servicios</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!--Services Page Start -->
    <section class="services-page">
        <div class="container">
            <div class="row">
                @foreach($services as $service)
                    <!--Services One Single Start -->
                    <div class="col-xl-4 col-lg-6 col-md-6">
                        <div class="services-one__single">
                            <div class="services-one__img">
                                <img src="{{ $service->imagePreview() }}" alt="{{ $service->name }}">
                            </div>
                            <div class="services-one__content">
                                <div class="services-one__count">{{ $loop->iteration }}</div>
                                <div class="services-one__icon">
                                    <span class="icon-shield"></span>
                                </div>
                                <h3 class="services-one__title"><a href="{{ route('web.service.show', $service) }}">{{ $service->name }}</a></h3>
                                <p class="services-one__text">{{ $service->fragment }}</p>
                                <a href="{{ route('web.service.show', $service) }}" class="services-one__btn">Ver más <span class="fas fa-arrow-right"></span></a>
                            </div>
                        </div>
                    </div>
                    <!--Services One Single End -->
                @endforeach
            </div>
        </div>
    </section>
    <!--Privacy Notice End-->
@endsection