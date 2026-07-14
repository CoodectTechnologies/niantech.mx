<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default tax rate
    |--------------------------------------------------------------------------
    |
    | This default tax rate will be used when you make a class implement the
    | Taxable interface and use the HasTax trait.
    |
    */

    'tax' => 16,

    /*
    |--------------------------------------------------------------------------
    | The products already include tax
    |--------------------------------------------------------------------------
    |
    | If the variable is true, then the tax will no longer increase in the price of the product.
    |
    */

    'products_already_include_tax' => true,

    /*
    |--------------------------------------------------------------------------
    | The shipping methods already include tax
    |--------------------------------------------------------------------------
    |
    | If the variable is true, then the tax will no longer increase in the price of the shipping method.
    |
    */

    'shipping_methods_already_include_tax' => true,

    /*
    |--------------------------------------------------------------------------
    | Shoppingcart database settings
    |--------------------------------------------------------------------------
    |
    | Here you can set the connection that the shoppingcart should use when
    | storing and restoring a cart.
    |
    */

    'database' => [

        'connection' => null,

        'table' => 'shoppingcart',

    ],

    /*
    |--------------------------------------------------------------------------
    | Destroy the cart on user logout
    |--------------------------------------------------------------------------
    |
    | When this option is set to 'true' the cart will automatically
    | destroy all cart instances when the user logs out.
    |
    */

    'destroy_on_logout' => false,

    /*
    |--------------------------------------------------------------------------
    | Default number format
    |--------------------------------------------------------------------------
    |
    | This defaults will be used for the formated numbers if you don't
    | set them in the method call.
    |
    */

    'format' => [

        'decimals' => 2,

        'decimal_point' => '.',

        'thousand_seperator' => ',',

    ],

];
