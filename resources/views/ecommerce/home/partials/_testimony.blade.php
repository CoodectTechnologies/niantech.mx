@if(count($comments))
    <div class="bg-grey pt-2  wolmart-sellers pb-5">
        <div class="container mt-0 mt-lg-10 mb-2 mb-lg-9">
            <div class="row">
                <div class="col-lg-4 mb-4 mb-lg-0">
                    <h4 class="text-primary font-weight-bold ls-25">Opiniones</h4>
                    <h2 class="title text-left ls-25">Lo que dicen nuestros clientes</h2>
                    <p>
                        Descubre las experiencias reales de nuestros clientes y la calidad de nuestros productos.
                    </p>
                </div>

                <div class="col-lg-8">
                    <div class="owl-carousel owl-theme row cols-sm-2" data-owl-options="{
                        'nav': false,
                        'dots': true,
                        'margin': 20,
                        'autoplay': true,
                        'autoplayTimeout': 4000,
                        'loop': false,
                        'responsive': {
                            '0': {
                                'items': 1
                            },
                            '576': {
                                'items': 2
                            }
                        }
                    }">

                        @foreach($comments as $comment)
                            <div class="testimonial-wrap">
                                <div class="testimonial testimonial-centered testimonial-boxed bg-white br-sm">
                                    <div class="testimonial-info">

                                        <figure class="testimonial-author-thumbnail">
                                            <img
                                                src="{{ $comment->user?->imagePreview() ?? asset('assets/admin/media/avatars/blank.png') }}"
                                                alt="{{ $comment->name }}"
                                                width="100"
                                                height="100"
                                            />
                                        </figure>

                                        <div class="ratings-container">
                                            <div class="ratings-full">
                                                <span
                                                    class="ratings"
                                                    style="width: {{ ($comment->stars * 100) / 5 }}%;">
                                                </span>
                                            </div>
                                        </div>

                                    </div>

                                    <blockquote>
                                        {{ Str::limit($comment->body, 150) }}
                                    </blockquote>

                                    <cite class="ls-25">
                                        {{ $comment->name }}
                                        @if(isset($comment->commentable))
                                            <span>{{ $comment->commentable->name }}</span>
                                        @endif
                                    </cite>
                                </div>
                            </div>
                        @endforeach

                    </div>
                </div>
            </div>
        </div>
    </div>
@endif