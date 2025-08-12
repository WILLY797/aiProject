<?php

return [
    'base_url' => env('EQUINOX_API_URL', ''),
    'api_key' => env('EQUINOX_API_KEY', ''),
    'timeout' => 15, // seconds
    'retry' => ['times' => 3, 'sleep' => 200], // ms
    'verify' => env('EQUINOX_VERIFY', true),
    'auth' => [
        'type' => 'header',
        'header' => env('EQUINOX_HEADER', 'x-EQUINOX-key'),
    ],
    'idempotency_ttl' => 3600, // seconds, if supported by
];

