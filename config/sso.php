<?php

return [
    'issuer' => env('SSO_ISSUER', env('APP_URL', 'http://localhost:8000')),
    'shared_secret' => env('SSO_SHARED_SECRET', 'change-this-shared-secret'),
    'token_ttl' => (int) env('SSO_TOKEN_TTL', 120),
];
