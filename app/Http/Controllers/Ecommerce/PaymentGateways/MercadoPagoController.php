<?php

namespace App\Http\Controllers\Ecommerce\PaymentGateways;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Ecommerce\Checkout\CheckoutController;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class MercadoPagoController extends Controller
{
    public static function payment(Request $request) {
        $paymentId = $request->data['id'];

        $response = Http::get(config('services.mercadopago.url').'payments/'.$paymentId.'?access_token='.config('services.mercadopago.token'));
        $response = json_decode($response);

        if ($response->status != 200) {
            return (array) $response;
        }

        $paymentStatus = $response->status; // 'pending', 'inprocess', 'inmediation', 'approved', 'cancelled', 'refunded', 'chargedback'
        $paymentMethod = $response->payment_type_id; // account_money: Money in the Mercado Pago account. ticket: Boletos, Caixa Electronica Payment, PayCash, Efecty, Oxxo, etc. bank_transfer: Pix and PSE (Pagos Seguros en Línea). atm: ATM payment (widely used in Mexico through BBVA Bancomer). credit_card: Payment by credit card. debit_card: Payment by debit card. prepaid_card: Payment by prepaid card. digital_currency: Purchases with Mercado Crédito. voucher_card: Alelo benefits, Sodexo. crypto_transfer: Payment with cryptocurrencies such as Ethereum and

        $orderNumber = $response->external_reference;
        $orderPaymentStatus = Order::PAYMENT_STATUS_REJECTED;

        if (! in_array($paymentStatus, ['pending', 'inprocess', 'inmediation'])) {
            $orderPaymentStatus = Order::PAYMENT_STATUS_REJECTED;
        }
        if (in_array($paymentStatus, ['approved'])) {
            $orderPaymentStatus = Order::PAYMENT_STATUS_APPROVED;
        }

        $order = Order::where('number', $orderNumber)->first();

        if ($order && $order->payment_status != Order::PAYMENT_STATUS_APPROVED) {
            $order->payment_status = $orderPaymentStatus;
            $order->payment_id = $paymentId;
            $order->payment_method = 'MercadoPago'.'-'.$paymentMethod;
            $order->payment_data = json_encode($response);
            $order->update();
            if ($response->status == 'approved') {
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
