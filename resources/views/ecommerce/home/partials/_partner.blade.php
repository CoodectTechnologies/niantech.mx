@if(count($partners))
    <div class="mt-10">
        <h2 class="subtitle d-flex justify-content-center pt-1 ls-normal text-dark mb-5">
            {{ __('Brands we trust') }}
        </h2>
    </div>
    <div class="owl-carousel owl-theme row cols-xl-8 cols-lg-6 cols-md-4 cols-sm-3 cols-2 brands-wrapper br-sm mb-10"
        data-owl-options="{
        'nav': true,
        'dots': false,
        'autoplay': true,
        'autoplayTimeout': 4000,
        'loop': true,
        'margin': 2,
        'responsive': {
            '0': {
                'items': 2
            },
            '576': {
                'items': 3
            },
            '768': {
                'items': 4
            },
            '992': {
                'items': 5
            },
            '1200': {
                'items': 5
            }
        }
        }">
        @foreach($partners as $partner)
            <figure>
                <img loading="lazy" src="{{ $partner->imagePreview() }}" alt="{{ $partner->name }}" width="290" />
            </figure>
        @endforeach
    </div>
@endif
