<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Password Policy
    |--------------------------------------------------------------------------
    */
    'password_max_age_days' => (int) env('UAM_PASSWORD_MAX_AGE_DAYS', 30),
    'password_notify_days' => [7, 3, 1],
    'password_history_count' => (int) env('UAM_PASSWORD_HISTORY_COUNT', 5),
    'reset_password_default' => env('UAM_RESET_PASSWORD_DEFAULT', 'DPT@SP3n'),

    /*
    |--------------------------------------------------------------------------
    | Login Security
    |--------------------------------------------------------------------------
    */
    'max_login_attempts' => (int) env('UAM_MAX_LOGIN_ATTEMPTS', 5),

    /*
    |--------------------------------------------------------------------------
    | Session
    |--------------------------------------------------------------------------
    */
    'session_timeout_minutes' => (int) env('UAM_SESSION_TIMEOUT', 15),
    'single_session' => (bool) env('UAM_SINGLE_SESSION', true),

    /*
    |--------------------------------------------------------------------------
    | Forgot Password
    |--------------------------------------------------------------------------
    */
    'forgot_password_token_ttl' => (int) env('UAM_FORGOT_TOKEN_TTL', 15),
];
