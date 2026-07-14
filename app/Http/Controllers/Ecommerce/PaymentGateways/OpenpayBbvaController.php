<?php

namespace App\Http\Controllers\Ecommerce\PaymentGateways;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Ecommerce\Checkout\CheckoutController;
use App\Models\Order;
use Illuminate\Http\Request;

class OpenpayBbvaController extends Controller
{
    public static function payment(Request $request) {
        $paymentId = $request['transaction']['id']; // Id del pago
        $orderNumber = $request['transaction']['description']; // Number order
        $paymentStatus = $request['transaction']['status']; // completed
        $paymentMethod = $request['transaction']['method']; // card
        $paymentAutorization = $request['transaction']['authorization']; // Ej: 801585
        $orderPaymentStatus = Order::PAYMENT_STATUS_PENDING;

        if (in_array($paymentStatus, ['completed']) && $paymentAutorization) {
            $orderPaymentStatus = Order::PAYMENT_STATUS_APPROVED;
        }

        $order = Order::where('number', $orderNumber)->first();

        if ($order && $order->payment_status != Order::PAYMENT_STATUS_APPROVED) {
            $order->payment_status = $orderPaymentStatus;
            $order->payment_id = $paymentId;
            $order->payment_method = 'Openpaybbva'.'-'.$paymentMethod;
            $order->payment_data = json_encode($request->all());
            $order->update();
            if ($paymentStatus == 'completed' && $paymentAutorization) {
                CheckoutController::processOrder($order);
            }
        }

        return [
            'paymentId' => $paymentId,
            'paymentStatus' => $paymentStatus,
            'orderPaymentStatus' => $orderPaymentStatus,
            'number' => $orderNumber,
        ];
    }
}
