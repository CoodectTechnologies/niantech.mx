<?php

namespace App\Services\Synchronizers\Order;

use App\Http\Controllers\Controller;
use App\Integrations\PCH;

// TODO: Logica de guias con ODOO y cambiar a Service y no Controller y elimianr el extends de Controller
class GuideService
{
    // public $provider;

    public function __construct() {
        // $this->provider = new PCH;
    }
    public static function create($orderProvider) {
        $orderController = new self;
        // $orderController->provider->createOrderGuide($orderProvider);
    }
}
