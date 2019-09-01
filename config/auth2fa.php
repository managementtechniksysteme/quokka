<?php

return [
    'otp_input' => env('AUTH2FA_OTPINPUT', 'one_time_password'),
    'otp_link_valid_minutes' => env('AUTH2FA_OTPLINKVALIDMINUTES', 2),
    'otp_route' => env('AUTH2FA_OTPROUTE', 'otp'),
    'otp_secret_column' => env('AUTH2FA_OTPSECRETCOLUMN', 'otp_secret'),
    'otp_window' => env('AUTH2FA_OTPWINDOW', 2),
];
