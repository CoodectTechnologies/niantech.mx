@component('mail::message')
# ¡Hola! Notamos que olvidaste tu carrito 🛒

@component('mail::panel')
Hemos guardado los productos que seleccionaste para que puedas recuperarlos fácilmente y no pierdas ninguna oferta.
@endcomponent

## Productos en tu carrito:
@foreach($products as $product)
<table style="margin-bottom: 16px;">
<tr>
<td style="vertical-align: middle;">
@if(isset($product->image))
<img src="{{ $product->imagePreview() }}" alt="{{ $product->name }}" width="60" style="border-radius:8px; margin-right:12px;">
@endif
</td>
<td style="vertical-align: middle;">
<a href="{{ route('ecommerce.product.show', $product) }}" style="font-size: 16px; font-weight: bold; color: #3869D4; text-decoration: none;">{{ $product->name }}</a>
</td>
</tr>
</table>
@endforeach

@component('mail::button', ['url' => route('login'), 'color' => 'success'])
Recuperar mi carrito
@endcomponent

Si tienes dudas o necesitas ayuda, responde a este correo y nuestro equipo te apoyará.

¡Gracias por confiar en {{ config('app.name') }}!

Saludos,
El equipo de {{ config('app.name') }}
@endcomponent
