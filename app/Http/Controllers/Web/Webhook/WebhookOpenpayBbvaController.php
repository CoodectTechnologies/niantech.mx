<?php

namespace App\Http\Controllers\Web\Webhook;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Ecommerce\PaymentGateways\OpenpayBbvaController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WebhookOpenpayBbvaController extends Controller
{
    public function __invoke(Request $request) {
        self::log('Webhook recibido', [
            'headers' => $request->headers->all(),
            'query' => $request->query(),
            'body' => $request->all(),
        ]);
        
        if ($request->transaction) {
            return OpenpayBbvaController::payment($request);
        } else {
            return [];
        }
    }
    private static function log($title, $url, $data = [], $response = []){
        Log::channel('openpay.webhook')->info('==========================================');
        Log::channel('openpay.webhook')->info($title);
        Log::channel('openpay.webhook')->info($url);
        Log::channel('openpay.webhook')->info('Data:', $data);
        Log::channel('openpay.webhook')->info('Response:', $response);
        Log::channel('openpay.webhook')->info('==========================================');
    }
}
