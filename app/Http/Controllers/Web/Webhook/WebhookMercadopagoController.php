<?php

namespace App\Http\Controllers\Web\Webhook;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Ecommerce\PaymentGateways\MercadoPagoController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WebhookMercadopagoController extends Controller
{
    public function __invoke(Request $request) {
        Log::alert('request notification:', $request->all());
        if ($request->data) {
            return MercadoPagoController::payment($request);
        } else {
            return [];
        }
    }
}
