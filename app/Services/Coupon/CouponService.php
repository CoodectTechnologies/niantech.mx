<?php

namespace App\Services\Coupon;

use App\Models\Coupon;
use Exception;

class CouponService
{
    public function apply(string $code, float $subtotal): array {
        $coupon = Coupon::where('code', $code)->first();
        if (! $coupon) {
            throw new Exception(__('The coupon does not exist'));
        }
        if (! $coupon->active) {
            throw new Exception(__('Inactive coupon'));
        }
        if ($coupon->isTimedOut()) {
            throw new Exception(__('Expired coupon'));
        }
        if ($coupon->isExceededLimitOfUse()) {
            throw new Exception(__('Limit of uses exceeded for this coupon'));
        }
        if (! $coupon->isCurrencySessionAllowed()) {
            throw new Exception(__('This coupon is not valid on currency:').currency());
        }
        if ($coupon->minimum_expense && $subtotal < $coupon->minimum_expense) {
            throw new Exception(__('Minimum expense').': '.$coupon->minimum_expense);
        }
        if ($coupon->isExcludePromotion()) {
            throw new Exception(__('This coupon is not applicable with products with promotion'));
        }
        if (! $coupon->isValidWithAllProductsInCart()) {
            throw new Exception(
                __('This coupon is not compatible with all your shopping cart items.')
            );
        }
        if ($coupon->type_coupon === 'Porcentaje') {
            $priceDiscount = ($subtotal * $coupon->percentage) / 100;
            $percentageDiscount = $coupon->percentage;
        } else {
            $priceDiscount = $coupon->fixed;
            $percentageDiscount = ($coupon->fixed / $subtotal) * 100;
        }

        return [
            'coupon' => $coupon,
            'price_discount' => $priceDiscount,
            'percentage_discount' => $percentageDiscount,
        ];
    }
}
