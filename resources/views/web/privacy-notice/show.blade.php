@extends('web.layouts.main')

@section('head')
    <title>Notaría Número 7 de Tlaquepaque - {{ $privacyNotice->title }}</title>
@endsection

@section('content')
    <section class="services-two">
        <div class="container">
            <div class="section-title text-center sec-title-animation animation-style1">
                <h2 class="section-title__title title-animation">{{ $privacyNotice->name }}</h2>
            </div>
        </div>
    </section>
    <!--Page Header Start-->

    <!--Privacy Notice Start-->
    <section class="blog-page">
        <div class="container">
            <div class="privacy-notice__content">
                {!! $privacyNotice->content !!}
            </div>
        </div>
    </section>
    <!--Privacy Notice End-->
@endsection