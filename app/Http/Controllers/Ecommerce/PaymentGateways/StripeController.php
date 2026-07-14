<?php

namespace App\Http\Controllers\Ecommerce\PaymentGateways;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Ecommerce\Checkout\CheckoutController;
use App\Models\Order;
use Exception;
use Illuminate\Http\Request;
use Stripe\StripeClient;

class StripeController extends Controller
{
    public static function payment(Request $request) {
        try {
            $eventType = $request['type'] ?? null;
            $data = $request['data']['object'] ?? [];
            $stripe = new StripeClient(config('services.stripe.secret'));
            $paymentIntentId = null;
            $paymentStatus = null;
            $orderNumber = null;

            switch ($eventType) {
                /*
                |--------------------------------------------------------------------------
                | 1. El cliente terminó el checkout
                |--------------------------------------------------------------------------
                | Ocurre cuando el usuario completa el flujo de pago (tarjeta, oxxo, etc.)
                | Para OXXO y SPEI, el pago aún no está confirmado en este punto.
                */
                case 'checkout.session.completed':
                    $paymentIntentId = $data['payment_intent'] ?? null;
                    $orderNumber = $data['metadata']['external_reference'] ?? null;
                    $paymentStatus = $data['payment_status'] ?? 'unpaid'; // paid, unpaid, no_payment_required

                    // No procesamos todavía si el pago no está confirmado
                    if ($paymentStatus === 'paid') {
                        self::updateOrder($stripe, $paymentIntentId, $orderNumber, Order::PAYMENT_STATUS_APPROVED, true);
                    } else {
                        self::updateOrder($stripe, $paymentIntentId, $orderNumber, Order::PAYMENT_STATUS_PENDING);
                    }
                    break;
                    /*
                    |--------------------------------------------------------------------------
                    | 2. El pago fue confirmado (OXXO, SPEI o tarjeta débito/crédito)
                    |--------------------------------------------------------------------------
                    */
                case 'payment_intent.succeeded':
                    $paymentIntentId = $data['id'];
                    $orderNumber = $data['metadata']['external_reference'] ?? null;
                    $paymentStatus = $data['status']; // requires_payment_method, requires_confirmation, requires_action, processing, requires_capture, canceled, or succeeded
                    if ($paymentStatus === 'succeeded') {
                        self::updateOrder($stripe, $paymentIntentId, $orderNumber, Order::PAYMENT_STATUS_APPROVED, true);
                    } else {
                        self::updateOrder($stripe, $paymentIntentId, $orderNumber, Order::PAYMENT_STATUS_PENDING);
                    }
                    break;
                    /*
                    |--------------------------------------------------------------------------
                    | 3. El pago falló o expiró (ej. OXXO vencido)
                    |--------------------------------------------------------------------------
                    */
                case 'payment_intent.payment_failed':
                    $paymentIntentId = $data['id'];
                    $orderNumber = $data['metadata']['external_reference'] ?? null;
                    self::updateOrder($stripe, $paymentIntentId, $orderNumber, Order::PAYMENT_STATUS_REJECTED);
                    break;
                    /*
                    |--------------------------------------------------------------------------
                    | 4. Reembolso procesado
                    |--------------------------------------------------------------------------
                    */
                case 'charge.refunded':
                    $chargeId = $data['id'];
                    $metadata = $data['metadata'] ?? [];
                    $orderNumber = $metadata['external_reference'] ?? null;
                    if ($orderNumber) {
                        $order = Order::where('number', $orderNumber)->first();
                        if ($order) {
                            $order->update([
                                'payment_status' => Order::PAYMENT_STATUS_REJECTED,
                            ]);
                        }
                    }
                    break;
            }

            return response()->json([
                'paymentIntentId' => $paymentIntentId,
                'paymentStatus' => $paymentStatus,
                'number' => $orderNumber,
            ], 200);
        } catch (Exception $e) {
            report($e);

            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    protected static function updateOrder(StripeClient $stripe, $paymentIntentId, $orderNumber, $status, $process = false) {
        if (! $orderNumber || ! $paymentIntentId) {
            return;
        }

        $order = Order::where('number', $orderNumber)->first();
        if (! $order) {
            return;
        }

        $response = $stripe->paymentIntents->retrieve($paymentIntentId, []);
        $paymentMethod = $response->payment_method_types[0] ?? 'unknown';

        if ($order->payment_status === Order::PAYMENT_STATUS_APPROVED && $status === Order::PAYMENT_STATUS_APPROVED) {
            return;
        }

        $order->update([
            'payment_status' => $status,
            'payment_id' => $paymentIntentId,
            'payment_method' => 'Stripe-'.$paymentMethod,
            'payment_data' => json_encode($response),
        ]);

        // Procesamos la orden solo si el pago fue confirmado
        if ($process && $status === Order::PAYMENT_STATUS_APPROVED) {
            CheckoutController::processOrder($order);
        }
    }
}
