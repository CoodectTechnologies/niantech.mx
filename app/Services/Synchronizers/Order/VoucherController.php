<?php

namespace App\Services\Synchronizers\Order;

use App\Http\Controllers\Controller;

// TODO: Checar si este se seguira ocupando o no
class VoucherController extends Controller
{
    public $provider;

    public function __construct() {
        // $this->provider = new PCH();
    }
    public static function create($orderProviderPayment) {
        // $orderController = new self();
        // $orderController->provider->createOrderPayment($orderProviderPayment);
    }
}
