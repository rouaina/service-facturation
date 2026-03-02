<?php
return [
    'host' => env('EUREKA_HOST', 'http://192.168.11.192:8761/eureka'),
    'app_name' => env('EUREKA_APP_NAME', 'service-facturation'),
    'port' => env('EUREKA_PORT', 8000),
    'renewal_interval' => 30,
    'duration_in_registry' => 90,
];

