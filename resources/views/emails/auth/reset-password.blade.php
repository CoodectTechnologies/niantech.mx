@component('mail::message')
# {{ __('Notificación de restablecimiento de contraseña') }}

{{ __('Has recibido este correo porque se solicitó un restablecimiento de contraseña para tu cuenta.') }}

@component('mail::button', ['url' => $url])
{{ __('Restablecer contraseña') }}
@endcomponent

{{ __('Este enlace para restablecer la contraseña expirará en :count minutos.', ['count' => $count]) }}

{{ __('Si no solicitaste un restablecimiento de contraseña, no es necesario realizar ninguna otra acción.') }}

@endcomponent
