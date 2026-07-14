<?php

namespace App\Http\Controllers\Web\Webhook;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Ecommerce\PaymentGateways\OpenpayBbvaController;
use Illuminate\Http\Request;

class WebhookOpenpayBbvaController extends Controller
{
    public function __invoke(Request $request) {
        if ($request->transaction) {
            return OpenpayBbvaController::payment($request);
        } else {
            return [];
        }
    }
}
