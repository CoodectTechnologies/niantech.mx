@component('mail::message')
# {{ __('Verificar dirección de correo electrónico') }}

{{ __('Por favor haz clic en el botón de abajo para verificar tu dirección de correo electrónico.') }}

@component('mail::button', ['url' => $url])
{{ __('Verificar dirección de correo electrónico') }}
@endcomponent

{{ __('Si no creaste una cuenta, no se requiere realizar ninguna acción.') }}

@endcomponent
