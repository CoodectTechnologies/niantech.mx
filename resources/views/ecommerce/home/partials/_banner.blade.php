@props([
    'banners' => [], 
    'btnDefault' => false,
    'isDescriptionExplode' => false,
    'classExtra' => [],
])

<div @class(['intro-section', ...$classExtra])>
    <div class="owl-carousel owl-theme owl-nav-inner owl-dot-inner row gutter-no cols-1 animation-slider"
        data-owl-options="{
            'nav': true,
            'dots': true,
            'items': 1,
            'autoplay': true,
            'autoplayTimeout': 4000,
            'autoHeight':true,
            'responsive': {
                '1630': {
                    'nav': true,
                    'dots': false
                }
            }
        }">
        @foreach ($banners as $banner)
            <div class="banner banner-fixed intro-slide intro-slide3"
                @if($banner->type == 'Imagen')
                    style="background-image: @if($banner->overlay) linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), @endif url({{ $banner->imagePreview() }});"
                @endif
                >
                @if($banner->type == 'Video')
                    <div class="hero">
                        <video src="{{ $banner->videoPreview() }}" autoplay muted loop playsinline preload="auto" type="video/mp4"></video>
                        @if($banner->overlay)
                            <div class="overlay"></div>
                        @endif
                    </div>
                @endif
                <div class="container">
                    <div class="banner-content y-50">
                        @if($banner->subtitle)
                            <h5 class="banner-subtitle text-uppercase text-primary font-weight-bold slide-animate"
                                data-animation-options="{'name': 'fadeInRightShorter', 'duration': '1s'}">{{ $banner->subtitle }}</h5>
                        @endif
                        @if($banner->title)
                            <h4 class="banner-title ls-25 slide-animate" data-animation-options="{'name': 'fadeInRightShorter', 'duration': '1s'}">{{ $banner->title }}</h4>
                        @endif
                        @if($banner->description)
                            @if(!$isDescriptionExplode)
                                <p class="banner-description ls-25 slide-animate text-white" data-animation-options="{'name': 'fadeInRightShorter', 'duration': '1s'}">
                                    {{ $banner->description }}
                                </p>                               
                            @else
                                @php($descriptionExplode = explode(',', $banner->description))
                                <ul class="banner-description-list">
                                    @foreach($descriptionExplode as $description)
                                        <li><i class="fa fa-circle-check text-primary"></i> {{ $description }}</li>
                                    @endforeach
                                </ul>
                            @endif
                        @endif
                        @if($banner->btn_text)
                            <a href="{{ $banner->btn_url }}" class="btn btn-primary btn-rounded btn-icon-right slide-animate mb-3 me-5" data-animation-options="{'name': 'fadeInRightShorter', 'duration': '1s'}">
                                {{ $banner->btn_text }}
                                <i class="w-icon-long-arrow-right"></i>
                            </a>
                        @endif
                        @if($btnDefault)
                            <a href="{{ route('ecommerce.contact.index') }}" class="btn btn-ligth btn-outline btn-rounded btn-icon-right slide-animate mb-3" data-animation-options="{'name': 'fadeInRightShorter', 'duration': '1s'}">
                                {{ __('Show catalog') }}
                                <i class="w-icon-long-arrow-right"></i>
                            </a>    
                        @endif
                        @if($banner->title)
                            <div class="d-flex g-5 mt-2 slide-animate" data-animation-options="{'name': 'fadeInRightShorter', 'duration': '1s'}">
                                <div class="icon-box icon-box-side text-white pr-5">
                                    <span class="icon-box-icon icon-shipping">
                                        <i class="w-icon-truck"></i>
                                    </span>
                                    <div class="icon-box-content">
                                        <h4 class="icon-box-title text-white">{{ __('Safe shipments') }}</h4>
                                    </div>
                                </div>
                                <div class="icon-box icon-box-side text-white pr-5">
                                    <span class="icon-box-icon icon-payment">
                                        <i class="w-icon-bag"></i>
                                    </span>
                                    <div class="icon-box-content">
                                        <h4 class="icon-box-title text-white">{{ __('Secure Payment') }}</h4>
                                    </div>
                                </div>
                                <div class="icon-box icon-box-side text-white pr-5">
                                    <span class="icon-box-icon icon-money">
                                        <i class="w-icon-money"></i>
                                    </span>
                                    <div class="icon-box-content">
                                        <h4 class="icon-box-title text-white">{{ __('Money back guarantee') }}</h4>
                                    </div>
                                </div>
                                <div class="icon-box icon-box-side text-white pr-5">
                                    <span class="icon-box-icon icon-chat">
                                        <i class="w-icon-chat"></i>
                                    </span>
                                    <div class="icon-box-content">
                                        <h4 class="icon-box-title text-white">{{ __('Customer Support') }}</h4>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                    <!-- End of .banner-content -->
                </div>
                <!-- End of .container -->
            </div>
            <!-- End of .intro-slide -->
        @endforeach
    </div>
</div>