<div>
    <!--Newsletter One Start -->
    <section class="newsletter-one">
        <div class="newsletter-one__big-text">
            Subscribete a nuestro newsletter
        </div>
        <div class="container">
            <div class="newsletter-one__inner">
                <div class="newsletter-one__left">
                    <h2 class="newsletter-one__title">Subscribete</h2>
                    <p class="newsletter-one__text"></p>
                </div>
                <div class="newsletter-one__right">
                    <form wire:submit.prevent="store" class="newsletter-one__form">
                        @if(session()->has('alert-subscriber'))
                            <div class="alert alert-{{ session()->get('alert-type-subscriber') }} alert-simple alert-inline">
                                <h4 class="alert-title">{{ session()->get('alert-subscriber') }}</h4>
                            </div>
                        @endif
                        <div class="newsletter-one__input">
                            <input wire:model="email" type="email" placeholder="Ingresa tu correo">
                        </div>
                        <button type="submit" class="thm-btn">
                            <span class="thm-btn-text">Subscribirme</span>
                            <span class="thm-btn-icon-box">
                                <i wire:loading.remove wire:target='store' class="fas fa-arrow-right"></i>
                                <span wire:loading wire:target='store' class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                            </span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </section>
    <!--Newsletter One End -->
</div>
