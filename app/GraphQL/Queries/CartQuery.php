<?php

namespace App\GraphQL\Queries;

use App\Models\Cart;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class CartQuery
{

    public function myCart(null $_, array $args, GraphQLContext $context, ResolveInfo $info): Cart
    {
        /** @var \App\Models\User $user */
        $user = $context->user();

        $cart = Cart::firstOrCreate(
            ['user_id' => $user->id],
            [
                'total_amount' => 0,
                'currency' => $user->default_currency ?? 'TRY',
            ]
        );
        
        $cart->load(['items.product']);

        return $cart;
    }
}

