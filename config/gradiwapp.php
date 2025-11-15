<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Base URL
    |--------------------------------------------------------------------------
    |
    | The base URL for the GradiWapp External API.
    | Example: https://api.your-gradiwapp-domain.com/external/v1
    |
    */
    'base_url' => env('GRADIWAPP_BASE_URL', 'https://api.your-gradiwapp-domain.com/external/v1'),

    /*
    |--------------------------------------------------------------------------
    | API Key
    |--------------------------------------------------------------------------
    |
    | Your GradiWapp API Key. You can obtain this from your client dashboard.
    |
    */
    'api_key' => env('GRADIWAPP_API_KEY'),

    /*
    |--------------------------------------------------------------------------
    | API Secret
    |--------------------------------------------------------------------------
    |
    | Your GradiWapp API Secret. This is used for HMAC signature generation.
    | Keep this secret secure and never commit it to version control.
    |
    */
    'api_secret' => env('GRADIWAPP_API_SECRET'),

    /*
    |--------------------------------------------------------------------------
    | Timeout
    |--------------------------------------------------------------------------
    |
    | HTTP request timeout in seconds.
    |
    */
    'timeout' => env('GRADIWAPP_TIMEOUT', 30),

    /*
    |--------------------------------------------------------------------------
    | Max Retries
    |--------------------------------------------------------------------------
    |
    | Maximum number of retry attempts for failed requests.
    |
    */
    'max_retries' => env('GRADIWAPP_MAX_RETRIES', 1),

    /*
    |--------------------------------------------------------------------------
    | Verify SSL
    |--------------------------------------------------------------------------
    |
    | Whether to verify SSL certificates when making HTTPS requests.
    | Set to false only for development/testing with self-signed certificates.
    |
    */
    'verify_ssl' => env('GRADIWAPP_VERIFY_SSL', true),
];

