<?php

namespace App\Http\Controllers\Ecommerce\Store;

use App\Http\Controllers\Controller;

class StoreController extends Controller
{
    public static function getStore() {
        $store = config('store.default');
        $segments = array_values(array_diff(request()->segments(), ['ecommerce']));
        $segment = isset($segments[0]) ? $segments[0] : null;
        if ($segment && in_array($segment, config('store.stores'))) {
            $store = $segment;
        }

        return $store;
    }
}
