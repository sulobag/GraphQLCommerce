<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\ServiceProvider;
use Laravel\Passport\Passport;

class AppServiceProvider extends ServiceProvider
{

    public function register(): void
    {
        
    }


    public function boot(): void
    {
        Model::shouldBeStrict(!$this->app->isProduction());
        Model::preventLazyLoading(!$this->app->isProduction());
    }
}
