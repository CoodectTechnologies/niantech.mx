<?php

namespace App\Http\Controllers\Web\Webhook;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Ecommerce\PaymentGateways\StripeController;
use Illuminate\Http\Request;

class WebhookStripeController extends Controller
{
    public function __invoke(Request $request) {
        if ($request->data) {
            return StripeController::payment($request);
        } else {
            return [];
        }
    }
}
