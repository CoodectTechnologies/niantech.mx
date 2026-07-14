<?php

namespace App\Services\Order;

use App\Models\Address;
use App\Models\Coupon;
use App\Models\Currency;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\Shoppingcart;
use App\Models\User;
use App\Services\Inventory\InventoryService;
use Gloudemans\Shoppingcart\Cart;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class OrderService
{
    public function __construct() {}
    public function create(User $user, Address $address, Cart $cart, array $data, ?Address $billingAddress, ?Coupon $coupon): Order {
        return DB::transaction(function () use ($user, $address, $cart, $data, $billingAddress, $coupon) {
            $currency = Currency::getCurrencyByCode(Session::get('currency'));
            $order = Order::create([
                'user_id' => $user->id,
                'address_id' => $address->id,
                'billing_address_id' => $billingAddress->id ?? null,
                'coupon_id' => $coupon?->id,
                'number' => null,
                'subtotal' => $data['subtotal'],
                'subtotal_tax' => $data['subtotalTax'],
                'subtotal_final' => $data['subtotalFinal'],
                'shipping_price' => $data['shippingPrice'],
                'shipping_price_tax' => $data['shippingPriceTax'],
                'shipping_price_final' => $data['shippingPriceFinal'],
                'shipping_method' => $data['shippingMethod'],
                'provider_shipping_method_id' => $data['shippingMethodProviderId'],
                'shipping_days' => $data['shippingDays'],
                'coupon_price_discount' => $data['couponPriceDiscount'] ?? null,
                'coupon_percentage_discount' => $data['couponPercentageDiscount'] ?? null,
                'tax' => $data['tax'],
                'total' => $data['totalPrice'],
                'currency' => $currency->code ?? 'MXN',
                'currency_value' => $currency?->value ?? 1,
            ]);
            $order->update(['number' => $this->generateOrderNumber($order)]);
            $inventoryService = new InventoryService;
            foreach ($cart->content() as $item) {
                $variantId = $item->options['variant']['id'] ?? null;
                $orderProduct = OrderProduct::create([
                    'order_id' => $order->id,
                    'product_id' => $item->model->id,
                    'product_variant_id' => $variantId,
                    'type' => $item->options->type,
                    'quantity' => $item->qty,
                    'price' => str_replace(config('cart.format.thousand_seperator'), '', $item->price),
                    'subtotal' => str_replace(config('cart.format.thousand_seperator'), '', $item->subtotal),
                ]);
                $inventoryService->createOrderProductWarehouses($orderProduct, $item->model, $variantId);
            }
            $this->cleanupCart($cart);

            return $order;
        });
    }
    public function generateOrderNumber(Order $order): string {
        $number = $order->id + 99;

        return 'P-'.str_pad($number, 8, '0', STR_PAD_LEFT);
    }
    protected function cleanupCart(Cart $cart): void {
        $cart->destroy();
        if (Auth::check()) {
            if ($shoppingCart = Shoppingcart::where('identifier', Auth::id())->first()) {
                $shoppingCart->delete();
            }
        }
    }
}
