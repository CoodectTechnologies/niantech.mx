@extends('web.layouts.main')

@section('head')
    <title>{{ __('Error page') }} 404</title>
@endsection

@section('content')
    <!--Page Header Start-->
    <section class="page-header">
        <div class="page-header__bg" style="background-image: url($banner->exists ? $banner->imagePreview() : '');">
        </div>
        <div class="container">
            <div class="page-header__inner">
                <h2>Error 404</h2>
                <div class="thm-breadcrumb__box">
                    <ul class="thm-breadcrumb list-unstyled">
                        <li><a href="{{ route('web.home.index') }}"><i class="fas fa-home"></i>Inicio</a></li>
                        <li><span class="icon-right-arrow-1"></span></li>
                        <li class="active">Página no encontrada</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>
    <!--Page Header End-->
@endsection
