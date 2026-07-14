<x-mail::app.layout>

{{-- Header --}}
<x-slot:header>
<x-mail::app.header :url="config('app.url')"></x-mail::app.header>
</x-slot:header>

{{-- Body --}}
{!! $slot !!}

{{-- Subcopy --}}
@isset($subcopy)
<x-slot:subcopy>
<x-mail::app.subcopy>
{!! $subcopy !!}
</x-mail::app.subcopy>
</x-slot:subcopy>
@endisset

{{-- Footer --}}
<x-slot:footer>
<x-mail::app.footer>
<div style="text-align:center; padding-bottom: 20px;">
@if(config('contact.facebook'))
<a class="me-2" href="{{ config('contact.facebook') }}" target="_blank"><img style="width: 40px" src="{{ asset('assets/admin/media/socials/facebook.png') }}" alt=""></a>
@endif
@if(config('contact.instagram'))
<a class="me-2" href="{{ config('contact.instagram') }}" target="_blank"><img style="width: 40px" src="{{ asset('assets/admin/media/socials/instagram.png') }}" alt=""></a>
@endif
@if(config('contact.x'))
<a class="me-2" href="{{ config('contact.x') }}" target="_blank"><img style="width: 40px" src="{{ asset('assets/admin/media/socials/x.png') }}" alt=""></a>
@endif
@if(config('contact.linkedin'))
<a class="me-2" href="{{ config('contact.linkedin') }}" target="_blank"><img style="width: 40px" src="{{ asset('assets/admin/media/socials/linkedin.png') }}" alt=""></a>
@endif
@if(config('contact.youtube'))
<a class="me-2" href="{{ config('contact.youtube') }}" target="_blank"><img style="width: 40px" src="{{ asset('assets/admin/media/socials/youtube.png') }}" alt=""></a>
@endif
@if(config('contact.tiktok'))
<a class="me-2" href="{{ config('contact.tiktok') }}" target="_blank"><img style="width: 40px" src="{{ asset('assets/admin/media/socials/tik-tok.png') }}" alt=""></a>
@endif
</div>

<div style="font-size: 13px; padding:0 15px; text-align:center; font-weight: 500; color: #A1A5B7;font-family:Arial,Helvetica,sans-serif">
<p style="text-align: center;">
© {{ date('Y') }} {{ config('app.name') }} {{ __('Todos los derechos reservados') }}
</p>
</div>
</x-mail::app.footer>
</x-slot:footer>

</x-mail::layout>
