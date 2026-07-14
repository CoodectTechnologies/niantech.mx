<?php

namespace App\Http\Controllers\Ecommerce\Checkout;

use App\Http\Controllers\Controller;
use App\Mail\Order\OrderChangeStatus;
use App\Mail\Order\OrderCreate;
use App\Mail\Order\OrderInfoBank;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Notifications\Order\OrderCreate as NotificationOrderCreate;
use App\Services\Synchronizers\Order\OrderController as OrderControllerProvider;
use Exception;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Redirect;
use Pusher\Pusher;

class CheckoutController extends Controller
{
    public function index() {
        if (! Cart::instance('default')->count()) {
            return Redirect::route('ecommerce.product.index');
        }

        return view('ecommerce.checkout.index');
    }
    public function payment(Order $order) {
        if (in_array($order->payment_status, [Order::PAYMENT_STATUS_APPROVED])) {
            return Redirect::route('ecommerce.checkout.complete', $order);
        }
        $order->load(['products', 'address.state.country']);

        return view('ecommerce.checkout.payment', compact('order'));
    }
    public function complete(Order $order) {
        if (! $order->payment_method) {
            return Redirect::route('ecommerce.checkout.payment', $order);
        }

        return view('ecommerce.checkout.complete', compact('order'));
    }
    public function whatsapp() {
        // GRETING
        $orderToString = '*Hola buen día, necesito cotización completa para este listado de productos, muchas gracias.*'.PHP_EOL;
        $orderToString .= '--------------------------------'.PHP_EOL;
        // STRING CONTENT CART
        foreach (Cart::instance('default')->content() as $item) {
            $orderToString .= '* '.$item->name.' ('.route('ecommerce.product.show', $item->model).') '.PHP_EOL;
        }
        $orderToString .= PHP_EOL.'--------------------------------'.PHP_EOL;
        // STRING SUMMARY
        $orderToString .= '*Subtotal: $'.number_format(str_replace(',', '', Cart::subtotal()), 2).'*'.PHP_EOL;

        return Redirect::to('https://wa.me/'.config('contact.whatsapp').'?text='.rawurlencode($orderToString));
    }
    public static function decrementStock($order, $reverse = false) {
        $order->load('orderProductWarehouses');
        if ($order->payment_status == Order::PAYMENT_STATUS_APPROVED || $reverse) {
            foreach ($order->orderProductWarehouses as $orderProductWarehouse) {
                $orderProduct = $orderProductWarehouse->orderProduct;
                if ($orderProduct->type == Product::TYPE_PHYSICAL) {
                    // Si tiene variante, descontar stock de la variante
                    if ($orderProduct->product_variant_id) {
                        $variant = $orderProduct->productVariant;
                        if ($variant) {
                            if ($reverse) {
                                $variant->productWarehouses()->updateExistingPivot(
                                    $orderProductWarehouse->product_warehouse_id,
                                    ['quantity' => DB::raw('quantity + '.$orderProductWarehouse->quantity)]
                                );
                            } else {
                                $variant->productWarehouses()->updateExistingPivot(
                                    $orderProductWarehouse->product_warehouse_id,
                                    ['quantity' => DB::raw('quantity - '.$orderProductWarehouse->quantity)]
                                );
                            }
                        }
                    } else {
                        // Si no tiene variante, descontar stock del producto base
                        $product = $orderProduct->product;
                        if ($product) {
                            if ($reverse) {
                                $product->productWarehouses()->updateExistingPivot(
                                    $orderProductWarehouse->product_warehouse_id,
                                    ['quantity' => DB::raw('quantity + '.$orderProductWarehouse->quantity)]
                                );
                            } else {
                                $product->productWarehouses()->updateExistingPivot(
                                    $orderProductWarehouse->product_warehouse_id,
                                    ['quantity' => DB::raw('quantity - '.$orderProductWarehouse->quantity)]
                                );
                            }
                        }
                    }
                }
            }
        }
    }
    public static function sendEmail($order) {
        try {
            Mail::to($order->address->email)->send(new OrderCreate($order));
            $order->send_email = true;
            $order->update();
        } catch (Exception $e) {
            report($e);
            $order->send_email = false;
            $order->send_email_error = $e->getMessage();
            $order->update();
        }
    }
    public static function sendEmailStatus($order) {
        try {
            Mail::to($order->address->email)->send(new OrderChangeStatus($order));
        } catch (Exception $e) {
            report($e);
        }
    }
    public static function sendEmailInfoBank($order) {
        try {
            Mail::to($order->address->email)->send(new OrderInfoBank($order));
            $order->send_email = true;
            $order->update();
        } catch (Exception $e) {
            $order->send_email = false;
            $order->send_email_error = $e->getMessage();
            $order->update();
        }
    }
    public static function sendNotificationAdmin($order) {
        try {
            Notification::send(User::permission(['ordenes'])->get(), new NotificationOrderCreate($order));
            self::sendNotificationPush($order);
        } catch (Exception $e) {
            report($e);
        }
    }
    public static function sendNotificationPush($order) {
        try {
            if (
                config('broadcasting.connections.pusher.key') &&
                config('broadcasting.connections.pusher.secret') &&
                config('broadcasting.connections.pusher.app_id')
            ) {
                $pusher = new Pusher(
                    config('broadcasting.connections.pusher.key'),
                    config('broadcasting.connections.pusher.secret'),
                    config('broadcasting.connections.pusher.app_id'),
                    [
                        'cluster' => config('broadcasting.connections.pusher.options.cluster'),
                        'useTLS' => true,
                    ]
                );
                $pusher->trigger('order-channel-'.config('app.env'), 'create', [
                    'title' => __('New order'),
                    'body' => $order->number,
                    'url' => route('admin.order.show', $order),
                ]);
            }
        } catch (Exception $e) {
            report($e);
        }
    }
    public static function sendProvider($order) {
        if ($order->hasProductProvider() && $order->payment_status == Order::PAYMENT_STATUS_APPROVED) {
            OrderControllerProvider::create($order);
        }
    }
    public static function processOrder($order) {
        self::decrementStock($order);
        self::sendEmail($order);
        self::sendNotificationAdmin($order);
        // self::sendProvider($order); //Ya no se enviará manualmente, solo por cron
    }
}
