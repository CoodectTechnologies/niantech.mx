<div>
    @include('web.components.alert')
    <form class="contact-two__form" wire:submit.prevent="sendEmail">
        <div class="row">
            <div class="col-xl-6">
                <div class="contact-two__input-box">
                    <input required type="text" placeholder="Tu Nombre" wire:model="emailWeb.name">
                    @error('emailWeb.name') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
            </div>
            <div class="col-xl-6">
                <div class="contact-two__input-box">
                    <input required type="email" placeholder="Correo Electrónico" wire:model="emailWeb.email">
                    @error('emailWeb.email') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
            </div>
            <div class="col-xl-6">
                <div class="contact-two__input-box">
                    <input required type="text" placeholder="Teléfono" wire:model="emailWeb.phone">
                    @error('emailWeb.phone') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
            </div>
            <div class="col-xl-6">
                <div class="contact-two__input-box">
                    <input required type="text" placeholder="Asunto" wire:model="emailWeb.subject">
                    @error('emailWeb.subject') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
            </div>
            <div class="col-xl-12">
                <div class="contact-two__input-box text-message-box">
                    <textarea placeholder="Escribe tu mensaje aquí..." wire:model="emailWeb.body"></textarea>
                    @error('emailWeb.body') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
                <x-web.honey.recaptcha action="contact"/>
                <div class="contact-two__btn-box">
                    <button type="submit" class="thm-btn">
                        <span class="thm-btn-text">Enviar Mensaje</span>
                        <span class="thm-btn-icon-box">
                            <i wire:loading.remove wire:target='sendEmail' class="fas fa-arrow-right"></i>
                            <span wire:loading wire:target='sendEmail' class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>
