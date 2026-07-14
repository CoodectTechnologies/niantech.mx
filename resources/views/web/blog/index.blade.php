@extends('web.layouts.main')

@section('head')
    <title>Notaría Número 7 de Tlaquepaque - Blog</title>
@endsection

@section('content')
    <section class="page-header">
        <div class="page-header__bg" style="background-image: url({{ $banner?->imagePreview() ?? '' }});">
        </div>
        <div class="container">
            <div class="page-header__inner">
                <h2>BLOG</h2>
                <div class="thm-breadcrumb__box">
                    <ul class="thm-breadcrumb list-unstyled">
                        <li><a href="{{ route('web.home.index') }}"><i class="fas fa-home"></i>Inicio</a></li>
                        <li><span class="icon-right-arrow-1"></span></li>
                        <li class="active"><a>Publicaciones</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!--Blog Page Start-->
    <section class="blog-page">
        <div class="container">
            <div class="row">
                @foreach($posts as $post)
                    <!--Blog One Single Start-->
                    <div class="col-xl-4 col-lg-4 col-md-6">
                        <div class="blog-one__single">
                            <div class="blog-one__img-box">
                                <div class="blog-one__img">
                                    <img src="{{ $post->imagePreview() }}" alt="{{ $post->name }}">
                                    @foreach($post->blogTags as $tag)
                                        <div class="blog-two__tags">
                                            <span>{{ $tag->name }}</span>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="blog-one__date">
                                    <p>{{ \Carbon\Carbon::parse($post->created_at)->format('d') }}</p>
                                    <span>{{ \Carbon\Carbon::parse($post->created_at)->format('M') }}</span>
                                </div>
                            </div>
                            <div class="blog-one__content">
                                <ul class="blog-one__meta list-unstyled">
                                    <li>
                                        <a href="#0">
                                            <span class="fas fa-user"></span>{{ $post->user->name ?? 'Autor' }}
                                        </a>
                                    </li>
                                </ul>
                                <h3 class="blog-one__title">
                                    <a href="{{ route('web.blog.show', $post) }}">{{ $post->name }}</a></h3>
                                <p class="blog-one__text">{{ $post->fragment }}</p>
                                <a href="{{ route('web.blog.show', $post) }}" class="blog-one__read-more">Ver más <span
                                        class="fas fa-arrow-right"></span></a>
                            </div>
                        </div>
                    </div>
                    <!--Blog One Single End-->
                @endforeach
                <!--Blog One Single End-->
                {{ $posts->links() }}
            </div>
        </div>
    </section>
    <!--Blog Page End-->
@endsection