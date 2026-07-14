<?php

namespace App\Services\Cart;

use Gloudemans\Shoppingcart\Cart;
use Gloudemans\Shoppingcart\CartItem;

class CustomCart extends Cart
{
    public function tax($decimals = null, $decimalSeparator = null, $thousandSeparator = null) {
        $decimalSeparator = $decimalSeparator ?? config('cart.format.decimal_point');
        $thousandSeparator = $thousandSeparator ?? config('cart.format.thousand_seperator');

        $originalTax = parent::tax($decimals, $decimalSeparator, $thousandSeparator);
        $customTax = $originalTax;

        if (config('cart.tax')) {
            $content = parent::getContent();
            $customTax = 0;
            $customTax = $content->reduce(function ($customTax, CartItem $cartItem) {
                $price = ($cartItem->price / (1 + (config('cart.tax') / 100)));
                $customTax += ($cartItem->qty * ($price * (config('cart.tax') / 100)));

                return $customTax;
            }, 0);
            $customTax = number_format($customTax, $decimals ?? 2, $decimalSeparator, $thousandSeparator);
        }

        return $customTax;
    }
    public function subtotal($decimals = null, $decimalSeparator = null, $thousandSeparator = null) {
        $decimalSeparator = $decimalSeparator ?? config('cart.format.decimal_point');
        $thousandSeparator = $thousandSeparator ?? config('cart.format.thousand_seperator');

        $originalSubtotal = parent::subtotal($decimals, $decimalSeparator, $thousandSeparator);
        $customSubtotal = $originalSubtotal;

        if (config('cart.tax')) {
            $content = parent::getContent();
            $customSubtotal = 0;
            $customSubtotal = $content->reduce(function ($customSubtotal, CartItem $cartItem) {
                $price = ($cartItem->price / (1 + (config('cart.tax') / 100)));
                $customSubtotal += ($cartItem->qty * $price);

                return $customSubtotal;
            }, 0);
            $customSubtotal = number_format($customSubtotal, $decimals ?? 2, $decimalSeparator, $thousandSeparator);
        }

        return $customSubtotal;
    }
    public function total($decimals = null, $decimalSeparator = null, $thousandSeparator = null) {
        $decimalSeparator = $decimalSeparator ?? config('cart.format.decimal_point');
        $thousandSeparator = $thousandSeparator ?? config('cart.format.thousand_seperator');

        $originalTotal = parent::total($decimals, $decimalSeparator, $thousandSeparator);
        $customTotal = $originalTotal;

        if (config('cart.tax')) {
            $content = parent::getContent();
            $customTotal = 0;
            $customTotal = $content->reduce(function ($customTotal, CartItem $cartItem) {
                $price = $cartItem->price;
                $customTotal += ($cartItem->qty * $price);

                return $customTotal;
            }, 0);
            $customTotal = number_format($customTotal, $decimals ?? 2, $decimalSeparator, $thousandSeparator);
        }

        return $customTotal;
    }
}
