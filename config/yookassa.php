<?php

return [
    // shop id
    'shop_id' => env('YOOKASSA_SHOP_ID', ''),

    // secret key
    'secret_key' => env('YOOKASSA_SECRET_KEY', ''),

    // Redirect URI
    'redirect_uri' => env('YOOKASSA_REDIRECT', ''),

    'ip_allow' => [
        '185.71.76.0/27',
        '185.71.77.0/27',
        '77.75.153.0/25',
        '77.75.156.11',
        '77.75.156.35',
        '77.75.154.128/25',
        '2a02:5180::/32'
    ]
];
