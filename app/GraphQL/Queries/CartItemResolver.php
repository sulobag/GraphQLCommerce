<?php

namespace App\GraphQL\Queries;

use App\Models\CartItem;

class CartItemResolver
{
    
    public function lineTotal(CartItem $cartItem): float
    {
        return $cartItem->quantity * $cartItem->unit_price;
    }
}

