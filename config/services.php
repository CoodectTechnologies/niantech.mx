<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'key' => env('POSTMARK_API_KEY'),
    ],

    'resend' => [
        'key' => env('RESEND_API_KEY'),
    ],

    'ses' => [
        'status' => (bool) env('AWS_STATUS'),
        'download_image_product' => (bool) env('AWS_DOWNLOAD_IMAGE_PRODUCT'),
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'google' => [
        'status' => (bool) env('GOOGLE_CLIENT_STATUS'),
        'client_id' => env('GOOGLE_CLIENT_ID'),
        'client_secret' => env('GOOGLE_CLIENT_SECRET'),
        'redirect' => env('GOOGLE_REDIRECT_URL'),
    ],

    'stripe' => [
        'status' => (bool) env('STRIPE_STATUS'),
        'erp_id' => env('STRIPE_ERP_ID'),
        'public' => env('STRIPE_KEY'),
        'secret' => env('STRIPE_SECRET'),
    ],

    'mercadopago' => [
        'status' => (bool) env('MERCADOPAGO_STATUS'),
        'erp_id' => env('MERCADOPAGO_ERP_ID'),
        'url' => env('MERCADOPAGO_URL'),
        'key' => env('MERCADOPAGO_PUBLIC_KEY'),
        'token' => env('MERCADOPAGO_ACCESS_TOKEN'),
        'country_code' => env('MERCADOPAGO_COUNTRY_CODE'),
        'currency_code' => env('MERCADOPAGO_CURRENCY_CODE'),
    ],

    'paypal' => [
        'status' => (bool) env('PAYPAL_STATUS'),
        'erp_id' => env('PAYPAL_ERP_ID'),
        'client_id' => env('PAYPAL_CLIENT_ID'),
    ],

    'openpay_bbva' => [
        'status' => (bool) env('OPENPAY_BBVA_STATUS'),
        'erp_id' => env('OPENPAY_BBVA_ERP_ID'),
        'url' => env('OPENPAY_BBVA_URL'),
        'id' => env('OPENPAY_BBVA_ID'),
        'private' => env('OPENPAY_BBVA_PRIVATE_KEY'),
        'public' => env('OPENPAY_BBVA_PUBLIC_KEY'),
        'country_code' => env('OPENPAY_BBVA_COUNTRY_CODE'),
    ],

    'transfer' => [
        'status' => (bool) env('TRANSFER_STATUS'),
        'erp_id' => env('TRANSFER_ERP_ID'),
        'account_bank' => env('TRANSFER_ACCOUNT_BANK'),
        'target' => env('TRANSFER_TARGET'),
        'bank' => env('TRANSFER_BANK'),
        'name' => env('TRANSFER_NAME'),
    ],

    'odoo' => [
        'status' => (bool) env('ODOO_STATUS'),
        'url' => env('ODOO_URL'),
        'database' => env('ODOO_DATABASE'),
        'key' => env('ODOO_KEY'),
    ],

    'vadeto_brands' => [
        'status' => (bool) env('BRANDS_STATUS'),
        'allowed' => explode(',', strtolower(env('BRANDS_ALLOWED'))),
        'download_image_product' => (bool) env('BRANDS_DOWNLOAD_IMAGE_PRODUCT'),
        'url' => env('BRANDS_URL'),
        'user' => env('BRANDS_USER'),
        'pass' => env('BRANDS_PASS'),
    ],

    'chatbot' => [
        'status' => (bool) env('CHATBOT_STATUS', false),
    ],

    'openai' => [
        'api_key' => env('OPENAI_API_KEY'),
    ],

];
