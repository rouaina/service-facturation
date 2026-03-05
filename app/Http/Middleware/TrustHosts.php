<?php

namespace App\Http\Middleware;

use Illuminate\Http\Middleware\TrustHosts as Middleware;

class TrustHosts extends Middleware
{
    /**
     * Get the host patterns that should be trusted.
     *
     * @return array<int, string|null>
     */
    public function hosts(): array
    {
        return [
            $this->allSubdomainsOfApplicationUrl(),
            'healthcheck.railway.app', // Essentiel pour Railway
            'localhost',
            '127.0.0.1',
            '*.up.railway.app', // Tous les sous-domaines Railway
        ];
    }
}
