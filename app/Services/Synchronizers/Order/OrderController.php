<?php

namespace App\Services\Synchronizers\Order;

use App\Http\Controllers\Controller;
use App\Integrations\ERP;
use App\Integrations\Odoo;
use App\Integrations\PCH;
use App\Models\Order;
use App\Models\OrderProviderError;

class OrderController extends Controller
{
    public $odoo;

    public function __construct() {
        $this->odoo = new Odoo;
    }
    public static function save() {
        // $failedOrderIds = OrderProviderError::query()
        //     ->groupBy('order_id')
        //     ->havingRaw('SUM(retry_limit) >= 3')
        //     ->pluck('order_id');

        // $orders = Order::query()
        //     ->with('orderProductWarehouses.productWarehouse')
        //     ->with('orderProductWarehouses.orderProduct.product')
        //     ->with('address.state')
        //     ->whereHas('orderProductWarehouses', function ($query) {
        //         $query->where('apply_provider', true);
        //     })
        //     ->whereDoesntHave('orderProviders')
        //     ->whereNotNull('payment_method')
        //     ->when(! config('services.erp.status'), function ($query) {
        //         return $query->validateOrder();
        //     }, function ($query) {
        //         return $query->whereNotIn('status', [Order::STATUS_CANCELED, Order::STATUS_REFUND]);
        //     })
        //     ->whereNotIn('orders.id', $failedOrderIds)
        //     ->get();
        // foreach($orders as $order) {
        //     self::create($order);
        // }
    }
    public static function create($order) {
        // $providersCode = $order->getProvidersCode();
        // $orderController = new self();
        // if (config('services.erp.status')) {
        //     $orderController->erp->createOrder($order);
        // } elseif (config('services.pch.status')) {
        //     if (in_array($orderController->pch->code, $providersCode)) {
        //         $orderController->pch->createOrders($order);
        //     }
        // }
    }
}
