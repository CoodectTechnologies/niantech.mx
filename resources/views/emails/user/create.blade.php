@component('mail::message')
Bienvenid@ {{ $user->name }}
@if($password)
{{__("Your password is:")}} {{ $password }}
@component('mail::button', ['url' => $url])
{{__("Change your password to a personalized one")}}
@endcomponent
@endif

@component('mail::subcopy')
{{ config('app.name') }}
@endcomponent
@endcomponent
