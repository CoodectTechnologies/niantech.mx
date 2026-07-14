<?php

namespace App\Services\Synchronizers\Order;

use App\Http\Controllers\Controller;
use App\Integrations\PCH;

class GuideController extends Controller
{
    public $provider;

    public function __construct() {
        $this->provider = new PCH;
    }
    public static function create($orderProvider) {
        $orderController = new self;
        $orderController->provider->createOrderGuide($orderProvider);
    }
}
