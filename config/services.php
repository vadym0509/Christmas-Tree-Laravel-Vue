<?php

use Purcell\Hone\Models\Member;

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Stripe, Mailgun, SparkPost and others. This file provides a sane
    | default location for this type of information, allowing packages
    | to have a conventional place to find your various credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'sparkpost' => [
        'secret' => env('SPARKPOST_SECRET'),
    ],

    'stripe' => [
        'model' => App\Models\Member::class,
        'key' => env('STRIPE_KEY'),
        'secret' => env('STRIPE_SECRET'),
        'webhook' => [
            'secret' => env('STRIPE_WEBHOOK_SECRET'),
            'tolerance' => env('STRIPE_WEBHOOK_TOLERANCE', 300),
        ],
    ],

    'sagepay' => [
        'url' => env('SAGEPAY_URL'),
        'integrationKey' => env('SAGEPAY_INTEGRATION_KEY'),
        'integrationPassword' => env('SAGEPAY_INTEGRATION_PASSWORD'),
        'vendorName' => env('SAGEPAY_VENDOR_NAME'),
    ],

    'sagepay_ff' => [
        'url' => env('SAGEPAY_URL'),
        'integrationKey' => env('SAGEPAY_FF_INTEGRATION_KEY'),
        'integrationPassword' => env('SAGEPAY_FF_INTEGRATION_PASSWORD'),
        'vendorName' => env('SAGEPAY_FF_VENDOR_NAME'),
    ],

];
