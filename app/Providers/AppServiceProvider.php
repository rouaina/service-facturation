<?php

namespace App\Providers;
use Illuminate\Support\Facades\Schema;
// Ajoute cette ligne :
//use Roshandelpoor\LaravelEurekaDiscovery\EurekaService;
//use App\Services\EurekaService;
use Illuminate\Support\Facades\URL; // ← Ajoute cette ligne

use Illuminate\Support\ServiceProvider;
use Illuminate\Routing\UrlGenerator;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(UrlGenerator $url)
    {
        //Schema::defaultStringLength(191);
      
        \Illuminate\Support\Facades\Schema::defaultStringLength(191);

      //  EurekaService::register();
        // if (app()->environment('production')) {
            // URL::forceScheme('https');
        // }

         if (env('APP_ENV') == 'production') {
            $url->forceScheme('https');
        }
    }


    
}
