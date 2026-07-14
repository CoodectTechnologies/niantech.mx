<style>
.custom-modal-content {
    background: transparent;
    border: none;
    max-width: 85rem;
    position: relative;
}
.modal-body {
    display: flex;
    flex-direction: row;
    max-height: 85rem;
}
.modal-left {
    width: 50%;
    padding: 2rem;
    display: flex;
    flex-direction: column;
    padding: 40px 40px 40px 40px;
    border-radius: 20px 0px 0px 20px;
}
.modal-right {
    width: 50%;
    position: relative;
}
.modal-right img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    border-radius: 0px 20px 20px 0px;
}
.modal-close-btn {
    position: absolute;
    top: 10px;
    right: 10px;
    color: #000;
    font-size: 1.5rem;
    background: none;
    border: none;
}
#custom-modal{
    /* background: linear-gradient(rgb(0 0 0 / 14%), rgb(0 0 0 / 64%)), url({{ asset('assets/ecommerce/images/popup/background.webp') }}); */
    background-repeat: no-repeat;
    background-position: center;
    background-size: cover;
}
button.close {
    border-radius: 50%;
    border: 3px solid #000000;
    min-height: 20px;
    min-width: 20px;
}
@media (max-width: 576px) {
    .modal-left {
        width: 100%;
    }
    .modal-right {
        width: 100%;
    }
    .modal-body{
        flex-direction: column;
    }
    .modal-body {
        align-items: center;
    }
    .modal-left {
    max-width: 77%;
    padding: 20px 20px 20px 20px;
    border-radius: 20px 20px 0px 0px;
    }
    .modal-right {
        max-width: 77%;
    }
    .modal-right img {
        border-radius: 0px 0px 20px 20px;
    }
    .modal-close-btn {
        right: 14%;
    }
}
@media (min-width: 576px) {
    .modal-dialog {
        max-width: 85rem;
    }
}
</style>

<div class="modal fade" id="custom-modal" tabindex="-1" role="dialog" aria-labelledby="modalTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content custom-modal-content">
            <div class="modal-body d-flex p-0">
                <!-- Sección Izquierda: Formulario -->
                <div class="modal-left" style="background-color: {{ $popup->background_color }};">
                    <div class="newsletter-content">
                        <h4 class="text-uppercase font-weight-normal ls-25" style="color: {{ $popup->subtitle_color }}">{{ $popup->subtitle }}</h4>
                        <h2 class="ls-25" style="color: {{ $popup->title_color }}">{{ $popup->title }}</h2>
                        <p class="ls-10" style="color: {{ $popup->description_color }}">{{ $popup->description }}</p>
                        <div class="mt-10">
                            @if($popup->newsletter)
                                @livewire('ecommerce.newsletter.popup')
                            @else
                                @if($popup->btn_text)
                                    <a href="{{ $popup->btn_url }}" class="btn btn-primary btn-block">
                                        {{ $popup->btn_text }}
                                    </a>
                                @endif
                            @endif
                        </div>
                        <div class="form-checkbox justify-content-center d-flex align-items-center">
                            <input type="checkbox" class="custom-checkbox" id="hide-newsletter-popup" name="hide-newsletter-popup" required="">
                            <label for="hide-newsletter-popup" class="font-size-sm text-light">{{ __("Don't show this popup again.") }}</label>
                        </div>
                    </div>
                </div>
                <!-- Sección Derecha: Imagen -->
                <div class="modal-right">
                    <img src="{{ $popup->imagePreview() }}" alt="Imagen de producto">
                </div>
            </div>
            <!-- Botón de cierre -->
            <button type="button" id="close-modal-popup" class="close modal-close-btn" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    </div>
</div>
@push('footer')
    <script>
        $(document).ready(function(){
            if(Coodect.getCookie('hideNewsletterPopup') !== 'true'){
                @if($popup->active)
                    $('#custom-modal').modal('show');
                @endif
            }
            $('#hide-newsletter-popup').click(function(e){
                if(this.checked){
                    Coodect.setCookie('hideNewsletterPopup', true, 7)
                }else{
                    Coodect.setCookie('hideNewsletterPopup', true, -14)
                }
            });
        });
    </script>
@endpush
