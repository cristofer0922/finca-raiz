<?php
return [
    'openai' => [
        'key'   => env('OPENAI_API_KEY'),
        'model' => env('OPENAI_MODEL', 'gpt-4o-mini'),
    ],
    'google' => [
        'maps_key' => env('GOOGLE_MAPS_API_KEY'),
    ],
    'whatsapp' => [
        'token'    => env('WHATSAPP_TOKEN'),
        'phone_id' => env('WHATSAPP_PHONE_ID'),
        'number'   => env('WHATSAPP_NUMBER', '573000000000'),
    ],
    'mercadopago' => [
        'token'      => env('MP_ACCESS_TOKEN'),
        'public_key' => env('MP_PUBLIC_KEY'),
    ],
    'stripe' => [
        'secret'         => env('STRIPE_SECRET'),
        'publishable'    => env('STRIPE_PUBLISHABLE'),
        'webhook_secret' => env('STRIPE_WEBHOOK_SECRET'),
    ],
];
