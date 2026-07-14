@component('mail::message')

<div style="text-align:center; margin-bottom: 24px;">
	<img loading="lazy" src="{{ asset('') . $model->imagePreview() }}" alt="{{ $model->name }}" width="100%">
</div>

@component('mail::panel')
<strong>{{ $title }}</strong>
@endcomponent

<p><strong>{{ __('Nombre') }}:</strong> {{ $comment->name }}</p>
<p><strong>{{ __('Correo electrónico') }}:</strong> {{ $comment->email }}</p>
<p><strong>{{ __('Mensaje') }}:</strong><br>{{ $comment->body }}</p>

@component('mail::button', ['url' => $url])
{{ __('View comment.') }}
@endcomponent

@endcomponent
