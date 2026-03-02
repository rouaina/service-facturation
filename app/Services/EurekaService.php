<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class EurekaService
{
    public static function register()
    {
        try {

            $host = config('eureka.host');
            $appName = strtoupper(config('eureka.app_name'));
            $port = config('eureka.port');
            $ip = gethostbyname(gethostname());

            Log::info("🚀 Tentative connexion Eureka", [
                'host' => $host,
                'app' => $appName,
                'ip' => $ip,
                'port' => $port
            ]);

            $response = Http::withHeaders([
                'Content-Type' => 'application/json'
            ])->post($host . '/apps/' . $appName, [
                'instance' => [
                    'hostName' => $ip,
                    'app' => $appName,
                    'ipAddr' => $ip,
                    'status' => 'UP',
                    'port' => [
                        '$' => $port,
                        '@enabled' => true
                    ],
                    'dataCenterInfo' => [
                        '@class' => 'com.netflix.appinfo.InstanceInfo$DefaultDataCenterInfo',
                        'name' => 'MyOwn'
                    ]
                ]
            ]);

            Log::info("✅ Réponse Eureka", [
                'status' => $response->status(),
                'body' => $response->body()
            ]);

        } catch (\Exception $e) {
            Log::error("❌ Eureka connection failed", [
                'error' => $e->getMessage()
            ]);
        }
    }
}