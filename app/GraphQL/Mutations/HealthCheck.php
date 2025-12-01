<?php

namespace App\GraphQL\Mutations;

class HealthCheck
{

    public function ping(?string $message = null): string
    {
        $suffix = $message ? sprintf(' (%s)', $message) : '';

        return 'pong' . $suffix;
    }
}

