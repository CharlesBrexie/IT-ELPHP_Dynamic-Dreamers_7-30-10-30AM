<?php

return [
    'paths' => ['api/*'],
    'allowed_methods' => ['*'],
    'allowed_origins' => ['*'], // Restrict to specific origins if needed
    'allowed_headers' => ['*'],
    'supports_credentials' => false,
];
