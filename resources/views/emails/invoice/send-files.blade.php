@component('mail::message')
    @component('mail::panel')
        Factura: {{ $invoice->folio }}
    @endcomponent

    {{ config('app.name') }}
@endcomponent
