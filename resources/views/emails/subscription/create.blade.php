@component('mail::app.message')

<div class="text-center mx-3 mb-5">
    <div class="mb-3">
        <img alt="Susbcription image" src="{{ url(asset('assets/admin/media/email/icon-positive-vote-3.png')) }}" />
    </div>
    <div class="mb-4 fs-6 fw-medium font-sans">
        <p style="text-align:center; margin-bottom:9px; color:#181C32; font-size: 22px; font-weight:700">{{ __('Subscripción creada con éxito') }}</p>
        <p style="text-align:center; margin-bottom:2px; color:#7E8299">Hola {{ $subscription->user->name }},</p>
        <p style="text-align:center; margin-bottom:2px; color:#7E8299">Confirmamos que tu suscripción en {{ config('app.name') }} ha sido creada exitosamente.</p>
        <p style="text-align:center; margin-bottom:2px; color:#7E8299">Desde ahora, tienes acceso a los beneficios y herramientas incluidos en tu plan.</p>
    </div>
</div>

@component('mail::app.table', ['title' => 'Resumen'])
    <table width="100%" cellpadding="0" cellspacing="0" role="presentation">
        <tr>
            <td align="left" style="color:#7E8299; font-size:14px; font-weight:500; font-family:Arial, sans-serif;">
                {{ $subscription->plan->title }}
            </td>
            <td align="right" style="color:#7E8299; font-size:14px; font-weight:500; font-family:Arial, sans-serif;">
                ${{ number_format($subscription->plan->amount, 2) }}
            </td>
        </tr>
    </table>
    <hr style="border: none; border-top: 1px dashed #E4E6EF; margin: 15px 0;">
    <table width="100%" cellpadding="0" cellspacing="0" role="presentation">
        <tr>
            <td align="left" style="color:#7E8299; font-size:14px; font-weight:500; font-family:Arial, sans-serif;">
                {{ __('Total') }}
            </td>
            <td align="right" style="color:#50cd89; font-size:14px; font-weight:700; font-family:Arial, sans-serif;">
                ${{ number_format($subscription->plan->amount, 2) }}
            </td>
        </tr>
    </table>
@endcomponent

@component('mail::app.subcopy')
    <p style="text-align: start; margin-bottom:2px">
        {{ __('Saludos') }}, {{ config('app.name') }}
    </p>
@endcomponent

@endcomponent

