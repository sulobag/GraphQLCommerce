<?php

namespace App\GraphQL\Queries;

use App\Models\Address;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class AddressQuery
{
    public function myAddresses(null $_, array $args, GraphQLContext $context, ResolveInfo $info)
    {
        /** @var \App\Models\User $user */
        $user = $context->user();

        return Address::where('user_id', $user->id)
            ->orderBy('is_primary', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();
    }
    
    public function address(null $_, array $args, GraphQLContext $context, ResolveInfo $info): Address
    {
        /** @var \App\Models\User $user */
        $user = $context->user();

        return Address::where('user_id', $user->id)
            ->findOrFail($args['id']);
    }
}

