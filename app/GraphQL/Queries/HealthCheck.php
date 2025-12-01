<?php

namespace App\GraphQL\Queries;

use Illuminate\Support\Str;

class HealthCheck
{
    
    public function version(): string
    {
        $app = config('app.name', 'GraphQLCommerce');
        $environment = app()->environment();

        return Str::of($app)
            ->append('::')
            ->append($environment)
            ->append('::v0-bootstrap')
            ->toString();
    }
}

