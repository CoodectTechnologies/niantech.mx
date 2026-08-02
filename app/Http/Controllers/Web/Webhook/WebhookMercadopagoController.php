<?php

namespace App\Http\Controllers\Web\Webhook;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Ecommerce\PaymentGateways\MercadoPagoController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WebhookMercadopagoController extends Controller
{
    public function __invoke(Request $request) {
        self::log('Webhook recibido', [
            'headers' => $request->headers->all(),
            'query' => $request->query(),
            'body' => $request->all(),
        ]);

        if ($request->data) {
            return MercadoPagoController::payment($request);
        } else {
            return [];
        }
    }
    private static function log($title, $url, $data = [], $response = []){
        Log::channel('mercadopago.webhook')->info('==========================================');
        Log::channel('mercadopago.webhook')->info($title);
        Log::channel('mercadopago.webhook')->info($url);
        Log::channel('mercadopago.webhook')->info('Data:', $data);
        Log::channel('mercadopago.webhook')->info('Response:', $response);
        Log::channel('mercadopago.webhook')->info('==========================================');
    }
}
