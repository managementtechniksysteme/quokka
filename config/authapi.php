<?php

return [
    'token_name_input' => 'device_name',
    'auth_token_ability' => 'authenticate',
    'refresh_token_ability' => 'refresh',
    'auth_token_lifetime' => env('AUTHAPI_AUTH_TOKEN_LIFETIME', 120),
    'refresh_token_lifetime' => env('AUTHAPI_REFRESH_TOKEN_LIFETIME', 43200),
];
