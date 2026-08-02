<?php

namespace App\Http\Controllers\Web\Webhook;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Ecommerce\PaymentGateways\StripeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WebhookStripeController extends Controller
{
    public function __invoke(Request $request) {
        self::log('Webhook recibido', [
            'headers' => $request->headers->all(),
            'query' => $request->query(),
            'body' => $request->all(),
        ]);
        
        if ($request->data) {
            return StripeController::payment($request);
        } else {
            return [];
        }
    }
    private static function log($title, $url, $data = [], $response = []){
        Log::channel('stripe.webhook')->info('==========================================');
        Log::channel('stripe.webhook')->info($title);
        Log::channel('stripe.webhook')->info($url);
        Log::channel('stripe.webhook')->info('Data:', $data);
        Log::channel('stripe.webhook')->info('Response:', $response);
        Log::channel('stripe.webhook')->info('==========================================');
    }
}
