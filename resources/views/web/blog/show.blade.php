@extends('web.layouts.main')

@section('head')
    <title>Notaría Número 7 de Tlaquepaque - {{ $post->name }}</title>
@endsection

@section('content')
    <!--Page Header Start-->
    <section class="page-header">
        <div class="page-header__bg" style="background-image: url(assets/images/backgrounds/page-header-bg.jpg);">
        </div>
        <div class="container">
            <div class="page-header__inner">
                <h2>{{ $post->name }}</h2>
                <div class="thm-breadcrumb__box">
                    <ul class="thm-breadcrumb list-unstyled">
                        <li><a href="{{ route('web.home.index') }}"><i class="fas fa-home"></i>Inicio</a></li>
                        <li><span class="icon-right-arrow-1"></span></li>
                        <li><a href="{{ route('web.blog.index') }}">Blog</a></li>
                        <li><span class="icon-right-arrow-1"></span></li>
                        <li class="active">{{ $post->name }}</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>
    <!--Page Header End-->

    <!-- Blog Details Start-->
    <section class="blog-details">
        <div class="container">
            <div class="row">
                <div class="col-xl-8 col-lg-7">
                    <div class="blog-details__content">
                        <div class="blog-details__img-1">
                            <div class="inner">
                                <img src="{{ $post->imagePreview() }}" alt="{{ $post->name }}">
                            </div>
                        </div>

                        <div class="blog-details__content-meta-box">
                            <ul class="list-unstyled">
                                <li>
                                    <div class="img-box">
                                        <img src="{{ $post->user->imagePreview() }}" alt="{{ $post->user->name ?? 'Autor' }}">
                                    </div>

                                    <div class="text-box">
                                        <p>Por - {{ $post->user->name ?? 'Autor' }}</p>
                                    </div>
                                </li>

                                <li>
                                    <div class="icon">
                                        <span class="icon-calendar"></span>
                                    </div>

                                    <div class="text-box">
                                        <p>{{ \Carbon\Carbon::parse($post->created_at)->format('F d, Y') }}</p>
                                    </div>
                                </li>

                                {{-- <li>
                                    <div class="icon">
                                        <span class="icon-bubble-chat-1"></span>
                                    </div>

                                    <div class="text-box">
                                        <p>Comment(5)</p>
                                    </div>
                                </li> --}}
                            </ul>
                        </div>

                        <div class="blog-details__content-text1">
                            <h2>{{ $post->name }}</h2>
                            <p>{{ $post->fragment }}</p>
                            <div>{!! $post->body !!}</div>
                        </div>

                        <div class="blog-details__content-text5">
                            <div class="blog-details__content-text5-tag">
                                <div class="title-box">
                                    <h2>Etiquetas:</h2>
                                </div>
                                <ul class="list-unstyled">
                                    @foreach($post->blogTags as $tag)
                                        <li><a href="{{ route('web.blog.index', ['tag' => $tag->name]) }}">#{{ $tag->name }}</a></li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                        <div class="blog-details__content-text5">
                            <div class="blog-details__content-text5-tag">
                                <div class="title-box">
                                    <h2>Categorías:</h2>
                                </div>
                                <ul class="list-unstyled">
                                    @foreach($post->blogCategories as $category)
                                        <li><a href="{{ route('web.blog.index', ['category' => $category->slug]) }}">#{{ $category->name }}</a></li>
                                    @endforeach
                                </ul>
                            </div>
                            {{-- <div class="blog-details__content-text5-share">
                                <div class="title-box">
                                    <p>Compartir ahora</p>
                                </div>
                                <ul class="list-unstyled">
                                    <li><a href="#"><span class="icon-facebook-app-symbol"></span></a></li>
                                    <li><a href="#"><span class="icon-instagram"></span></a></li>
                                    <li><a href="#"><span class="icon-twitter"></span></a></li>
                                    <li><a href="#"><span class="icon-linkedin"></span></a></li>
                                </ul>
                            </div> --}}
                        </div>
                    </div>
                </div>

                <!--Start Sidebar-->
                <div class="col-xl-4 col-lg-5">
                    <div class="sidebar">

                        <!--Start Sidebar Single-->
                        <div class="sidebar__single sidebar__category wow fadeInUp" data-wow-delay=".1s">
                            <h3 class="sidebar__title">Categorías</h3>
                            <ul class="sidebar__category-list list-unstyled">
                                @foreach($post->blogCategories as $cat)
                                    <li><a href="{{ route('web.blog.index', ['category' => $cat->slug]) }}">{{ $cat->name }}</a></li>
                                @endforeach
                            </ul>
                        </div>
                        <!--End Sidebar Single-->

                        <!--Start Sidebar Single-->
                        <div class="sidebar__single sidebar__post wow fadeInUp" data-wow-delay=".1s">
                            <h3 class="sidebar__title">Posts recientes</h3>
                            <div class="sidebar__post-box">
                                @if(isset($recentPosts))
                                    @foreach($recentPosts as $recent)
                                        <div class="sidebar__post-single">
                                            <div class="sidebar-post__img">
                                                <img src="{{ $recent->imagePreview() }}" alt="{{ $recent->name }}">
                                            </div>
                                            <div class="sidebar__post-content-box">
                                                <h3><a href="{{ route('web.blog.show', $recent) }}">{{ $recent->name }}</a></h3>
                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                        <!--End Sidebar Single-->

                    </div>
                </div>
                <!--End Sidebar-->
            </div>
        </div>
    </section>
    <!--Blog Details End-->
@endsection