<?php

namespace App\Livewire\Ecommerce\Checkout;

use App\Http\Controllers\Ecommerce\Checkout\CheckoutController;
use App\Models\Order;
use Exception;
use Illuminate\Support\Facades\Redirect;
use Livewire\Component;
use MercadoPago\Client\Preference\PreferenceClient;
use MercadoPago\Exceptions\MPApiException;
use MercadoPago\MercadoPagoConfig;
use Openpay\Data\Openpay;
use Openpay\Data\OpenpayApiConsole;
use Stripe\StripeClient;

class Payment extends Component
{
    public $order;
    public $currency;
    public $productsProrate = [];
    public $stripeURL = null;
    public $mercadoPagoId = null;
    public $openpayBbvaURL = null;

    public function mount(Order $order) {
        $this->order = $order;
        $this->order->with(['productVariant', 'orderProducts', 'address', 'billingAddress']);
        $this->loadCurrency();
        $this->loadProductProrate();
        $this->loadStripe();
        $this->loadMercadoPago();
        $this->loadBbvaOpenpay();
    }
    public function render() {
        return view('livewire.ecommerce.checkout.payment');
    }
    private function loadCurrency() {
        $this->currency = strtoupper($this->order->currency);
    }
    public function paymentPayPal($data) {
        $data = json_decode(json_encode($data));
        $this->order->payment_status = Order::PAYMENT_STATUS_APPROVED;
        $this->order->payment_id = $data->orderID;
        $this->order->payment_method = 'PayPal';
        $this->order->payment_data = json_encode($data);
        $this->order->update();
        CheckoutController::processOrder($this->order);

        return Redirect::route('ecommerce.checkout.complete', $this->order);
    }
    public function paymentTransfer() {
        $this->order->payment_status = Order::PAYMENT_STATUS_PENDING;
        $this->order->payment_method = 'Transfer';
        $this->order->update();
        CheckoutController::sendEmailInfoBank($this->order);
        CheckoutController::sendNotificationAdmin($this->order);

        return Redirect::route('ecommerce.checkout.complete', $this->order);
    }
    private function loadStripe() {
        if (config('services.stripe.status')) {
            $stripe = new StripeClient(config('services.stripe.secret'));
            $lineItems = [];
            foreach ($this->productsProrate as $product) {
                $lineItems[] = [
                    'price_data' => [
                        'currency' => strtolower($this->currency),
                        'product_data' => [
                            'name' => $product['name'],
                        ],
                        'unit_amount' => round($product['price'] * 100, 0),
                    ],
                    'quantity' => $product['quantity'],
                ];
            }
            $checkoutSessionCreate = [
                'shipping_options' => [
                    [
                        'shipping_rate_data' => [
                            'type' => 'fixed_amount',
                            'fixed_amount' => [
                                'amount' => round(($this->order->shipping_price_final) * 100, 0),
                                'currency' => strtolower($this->currency),
                            ],
                            'display_name' => $this->order->shipping_method,
                        ],
                    ],
                ],
                'line_items' => $lineItems,
                'mode' => 'payment',
                'customer_email' => $this->order->address ? $this->order->address->email : '',
                'metadata' => [
                    'external_reference' => $this->order->number,
                ],
                'success_url' => route('ecommerce.checkout.complete', $this->order->number),
                'cancel_url' => route('ecommerce.checkout.payment', $this->order->number),
            ];
            if (! (int) $this->order->shipping_price) {
                unset($checkoutSessionCreate['shipping_options']);
            }
            try {
                $checkout_session = $stripe->checkout->sessions->create($checkoutSessionCreate);
                $this->stripeURL = $checkout_session->url;
            } catch (Exception $e) {
                report($e);
            }
        }
    }
    private function loadMercadoPago() {
        if (config('services.mercadopago.status')) {
            MercadoPagoConfig::setAccessToken(config('services.mercadopago.token'));
            $products = [];
            foreach ($this->productsProrate as $productId => $product) {
                $products[] = [
                    'id' => $productId,
                    'title' => $product['name'],
                    'description' => $product['detail'] ?? $product['name'],
                    'currency_id' => $this->currency,
                    'quantity' => $product['quantity'],
                    'unit_price' => (float) $product['price'],
                ];
            }
            $payer = [
                'name' => explode(' ', $this->order->address->name)[0],
                'surname' => explode(' ', $this->order->address->name)[1] ?? '',
                'email' => $this->order->address->email,
            ];
            $paymentMethods = [
                'excluded_payment_methods' => [],
                'installments' => 12,
                'default_installments' => 1,
            ];
            $backUrls = [
                'success' => route('ecommerce.checkout.complete', $this->order),
                'pending' => route('ecommerce.checkout.complete', $this->order),
                'failure' => route('ecommerce.checkout.payment', $this->order),
            ];
            $shipments = [
                'cost' => (float) $this->order->shipping_price_final,
                'mode' => 'not_specified',
            ];
            $request = [
                'items' => $products,
                'payer' => $payer,
                'payment_methods' => $paymentMethods,
                'back_urls' => $backUrls,
                'statement_descriptor' => config('app.name'),
                'external_reference' => $this->order->number,
                'expires' => false,
                'auto_return' => 'approved',
                'binary_mode' => true, // Los pagos pendientes o aún en proceso serán automáticamente rechazados por defecto
                'shipments' => $shipments,
            ];
            $client = new PreferenceClient;
            try {
                $preference = $client->create($request);
                $this->mercadoPagoId = $preference->id;
            } catch (MPApiException $error) {
                $this->mercadoPagoId = null;
                report($error);
            } catch (Exception $e) {
                $this->mercadoPagoId = null;
                report($e);
            }
        }
    }
    private function loadBbvaOpenpay() {
        if (config('services.openpay_bbva.status')) {
            Openpay::setProductionMode(! (bool) config('app.debug'));
            Openpay::setId(config('services.openpay_bbva.id'));
            Openpay::setApiKey(config('services.openpay_bbva.private'));
            OpenpayApiConsole::setLevel(OpenpayApiConsole::CONSOLE_DEBUG);
            $openpay = Openpay::getInstance(config('services.openpay_bbva.id'), config('services.openpay_bbva.private'), countryByLanguage()['code'], request()->ip());
            $address = $this->order->address;
            $fullName = explode(' ', $address->name);
            $clientData = [
                'name' => isset($fullName[0]) ? $fullName[0] : 'N/A',
                'last_name' => isset($fullName[1]) ? $fullName[1] : 'N/A',
                'email' => $address->email,
                'phone_number' => $address->phone,
            ];
            $paymentData = [
                'affiliation_bbva' => config('services.openpay_bbva.affiliation'),
                'method' => 'card',
                'amount' => $this->order->total,
                'description' => $this->order->number,
                'customer' => $clientData,
                'send_email' => false, // No enviar email automáticamente
                'confirm' => false, // No confirmar automáticamente
                'redirect_url' => route('ecommerce.checkout.complete', $this->order), // URL a donde redirigir al cliente después del pago
                'currency' => $this->currency,
            ];
            try {
                $charge = $openpay->charges->create($paymentData);
                $this->openpayBbvaURL = isset($charge->payment_method->url) ? $charge->payment_method->url : null;
            } catch (Exception $e) {
                report($e);
            }
        }
    }
    private function loadProductProrate() {
        $this->productsProrate = $this->order->productsProrate();
    }
}
