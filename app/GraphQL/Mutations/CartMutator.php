<?php

namespace App\GraphQL\Mutations;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use GraphQL\Type\Definition\ResolveInfo;
use Illuminate\Support\Facades\DB;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class CartMutator
{

    public function addToCart(null $_, array $args, GraphQLContext $context, ResolveInfo $info): Cart
    {
        /** @var \App\Models\User $user */
        $user = $context->user();
        $input = $args['input'];

        /** @var Product $product */
        $product = Product::where('is_active', true)->findOrFail($input['productId']);

        $quantity = max(1, (int) ($input['quantity'] ?? 1));

        $cart = DB::transaction(function () use ($user, $product, $quantity): Cart {
            $cart = Cart::firstOrCreate(
                ['user_id' => $user->id],
                [
                    'total_amount' => 0,
                    'currency' => $user->default_currency ?? 'TRY',
                ]
            );

            $cartItem = CartItem::where('cart_id', $cart->id)
                ->where('product_id', $product->id)
                ->first();

            if ($cartItem) {
                $cartItem->update([
                    'quantity' => $cartItem->quantity + $quantity,
                    'unit_price' => $product->price,
                ]);
            } else {
                CartItem::create([
                    'cart_id' => $cart->id,
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                    'unit_price' => $product->price,
                    'currency' => $product->currency,
                ]);
            }

            $cart->calculateTotal();

            return $cart->fresh(['items.product']);
        });

        return $cart;
    }


    public function updateCartItem(null $_, array $args, GraphQLContext $context, ResolveInfo $info): Cart
    {
        /** @var \App\Models\User $user */
        $user = $context->user();
        $input = $args['input'];

        $cart = Cart::where('user_id', $user->id)->firstOrFail();

        $cartItem = CartItem::where('cart_id', $cart->id)
            ->findOrFail($input['itemId']);

        $quantity = max(1, (int) ($input['quantity'] ?? 1));

        $cartItem->update([
            'quantity' => $quantity,
            'unit_price' => $cartItem->product->price,
        ]);

        $cart->calculateTotal();

        return $cart->fresh(['items.product']);
    }


    public function removeFromCart(null $_, array $args, GraphQLContext $context, ResolveInfo $info): Cart
    {
        /** @var \App\Models\User $user */
        $user = $context->user();

        $cart = Cart::where('user_id', $user->id)->firstOrFail();

        $cartItem = CartItem::where('cart_id', $cart->id)
            ->findOrFail($args['itemId']);

        $cartItem->delete();

        $cart->calculateTotal();

        return $cart->fresh(['items.product']);
    }


    public function clearCart(null $_, array $args, GraphQLContext $context, ResolveInfo $info): Cart
    {
        /** @var \App\Models\User $user */
        $user = $context->user();

        $cart = Cart::where('user_id', $user->id)->firstOrFail();

        $cart->items()->delete();
        $cart->update(['total_amount' => 0]);

        return $cart->fresh(['items.product']);
    }
}

