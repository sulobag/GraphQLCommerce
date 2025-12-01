<?php

namespace App\GraphQL\Mutations;

use GraphQL\Type\Definition\ResolveInfo;
use Illuminate\Support\Arr;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class ProfileMutator
{
    public function updateProfile(null $_, array $args, GraphQLContext $context, ResolveInfo $info)
    {
        /** @var \App\Models\User $user */
        $user = $context->user();

        $input = $args['input'];

        $user->fill([
            'name' => $input['name'] ?? $user->name,
            'phone' => $input['phone'] ?? $user->phone,
            'default_currency' => $input['defaultCurrency'] ?? $user->default_currency,
        ]);

        if (array_key_exists('preferences', $input)) {
            $user->preferences = $input['preferences'];
        }

        $user->save();

        return $user;
    }
}


