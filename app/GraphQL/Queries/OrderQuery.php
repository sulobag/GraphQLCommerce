<?php

namespace App\GraphQL\Queries;

use App\Models\Order;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class OrderQuery
{
    public function forCurrentUser(null $_, array $args, GraphQLContext $context, ResolveInfo $info)
    {
        /** @var \App\Models\User $user */
        $user = $context->user();

        return Order::where('user_id', $user->id)
            ->latest('id')
            ->get();
    }

    public function order(null $_, array $args, GraphQLContext $context, ResolveInfo $info): Order
    {
        /** @var \App\Models\User $user */
        $user = $context->user();

        return Order::where('user_id', $user->id)
            ->with(['items.product', 'payments', 'shippingAddress', 'billingAddress'])
            ->findOrFail($args['id']);
    }
}


