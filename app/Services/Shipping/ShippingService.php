<?php

namespace App\Services\Shipping;

use App\Models\Currency;
use App\Models\Product;
use App\Models\ShippingZone;
use Carbon\Carbon;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class ShippingService
{
    public function applyShipping($cartInstance = 'default') {
        $allAreDigitales = true;
        foreach (Cart::instance($cartInstance)->content() as $item) {
            if ($item->options->type == Product::TYPE_PHYSICAL) {
                $allAreDigitales = false;
                break;
            }
        }

        return ! $allAreDigitales;
    }
    public static function getShippingMethods($stateId, $zipCode) {
        $shippingMethods = [];
        // switch(config('services.odoo.status')): // TODO: REMOVER EL false CUANDO YA SE VAYA A TRABAJAR LA PARTE DE LOS METODOS DE ENVIO DE ODOO
        switch (false) { // TODO: REMOVER EL false CUANDO YA SE VAYA A TRABAJAR LA PARTE DE LOS METODOS DE ENVIO DE ODOO
            case true:
                $shippingMethods = self::getByOdoo($shippingMethods, $zipCode);
                break;
            default:
                $shippingMethods = self::getByLocals($shippingMethods, $stateId, $zipCode);
                break;
        }

        return $shippingMethods;
    }
    public static function getByOdoo($shippingMethods, $zipCode) {
        $user = Auth::check() ? Auth::user() : null;
        $currency = Session::get('currency');
        $clientId = $user->provider_id;
        $products = [];
        foreach (Cart::instance('default')->content() as $item) {
            $products[] = [
                'sku' => $item->model->sku,
                'cantidad' => intval($item->qty),
                'precio' => $item->price,
            ];
        }

        return $shippingMethods;
    }
    public static function getByLocals($shippingMethods, $stateId, $zipCode) {
        $shippingZones = ShippingZone::query()
            ->has('states')
            ->with(['states', 'shippingClasses'])
            ->whereRelation('states', 'state_id', $stateId)
            ->get();
        foreach ($shippingZones as $shippingZone) {
            $addToShippingZone = [
                'id' => $shippingZone->id,
                'name' => $shippingZone->alias,
                'price' => self::getShippingPriceByZone($shippingZone),
                'days' => $shippingZone->shipping_days,
                'estimatedDate' => Carbon::parse(today())->addDays($shippingZone->shipping_days)->toFormattedDateString(),
            ];
            if (! $shippingZone->zip_codes || in_array($zipCode, explode(',', $shippingZone->zip_codes))) {
                $shippingMethods[$shippingZone->id] = $addToShippingZone;
            } else {
                foreach (explode(',', $shippingZone->zip_codes) as $zipCodeRange) {
                    $zipCodeRange = trim($zipCodeRange);
                    if (str_contains($zipCodeRange, '...')) {
                        [$zipCodeStart, $zipCodeEnd] = array_map('trim', explode('...', $zipCodeRange));
                        if (strcmp($zipCode, $zipCodeStart) >= 0 && strcmp($zipCode, $zipCodeEnd) <= 0) {
                            $shippingMethods[$shippingZone->id] = $addToShippingZone;
                            break;
                        }
                    }
                }
            }
        }

        return $shippingMethods;
    }
    public static function getShippingPriceByZone($shippingZone) {
        $subtotal = floatval(str_replace(',', '', Cart::instance('default')->subtotal()));
        $priceWithoutShippingClass = $shippingZone->price;
        $priceWithShippingClassQty = 0;
        $priceWithShippingClass = 0;
        $shippingClassIdsRepeat = [];
        if (! $shippingZone || ($shippingZone->free_shipping_over_to && $subtotal >= $shippingZone->free_shipping_over_to)) {
            return 0;
        }
        foreach (Cart::instance('default')->content() as $item) {
            if (! $item->model->shippingClass || $item->options->type != Product::TYPE_PHYSICAL) {
                continue;
            }
            $shippingClass = $shippingZone->shippingClasses->where('id', $item->model->shippingClass->id)->first();
            if ($shippingClass) {
                $pivotPrice = $shippingClass->pivot->price;
                if ($shippingClass->pivot->multiply_quantity) {
                    $multiplicate = intdiv($item->qty - 1, $shippingClass->pivot->multiply_quantity) + 1;
                    $priceWithShippingClassQty += $pivotPrice * $multiplicate;
                    $priceWithoutShippingClass = 0;
                } else {
                    if (! in_array($shippingClass->id, $shippingClassIdsRepeat)) {
                        $shippingClassIdsRepeat[] = $shippingClass->id;
                        $priceWithShippingClass += $pivotPrice;
                        $priceWithoutShippingClass = 0;
                    }
                }
            }
        }
        $hasProductsWithAndWithoutShippingClass = self::hasProductsWithAndWithoutShippingClass();
        if ($hasProductsWithAndWithoutShippingClass) {
            $priceWithoutShippingClass = $shippingZone->price;
        }
        $price = $priceWithShippingClassQty + $priceWithShippingClass + $priceWithoutShippingClass;
        $currency = Currency::getCurrencyByCode(Session::get('currency'));
        if ($currency && $currency->value) {
            $price = floatval(number_format($price / $currency->value, 2));
        }

        return $price;
    }
    protected static function hasProductsWithAndWithoutShippingClass() {
        $hasProductsWithAndWithoutShippingClass = false;
        $hasProductsWithShippingClass = false;
        $hasProductsWithoutShippingClass = false;
        foreach (Cart::instance('default')->content() as $item) {
            if ($item->model->shippingClass) {
                $hasProductsWithShippingClass = true;
            } else {
                $hasProductsWithoutShippingClass = true;
            }
        }
        if ($hasProductsWithShippingClass && $hasProductsWithoutShippingClass) {
            $hasProductsWithAndWithoutShippingClass = true;
        }

        return $hasProductsWithAndWithoutShippingClass;
    }
}
