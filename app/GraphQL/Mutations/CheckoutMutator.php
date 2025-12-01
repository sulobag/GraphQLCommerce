<?php

namespace App\GraphQL\Mutations;

use App\Jobs\ProcessCheckout;
use App\Models\Address;
use App\Models\Inventory;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use GraphQL\Type\Definition\ResolveInfo;
use Illuminate\Support\Facades\DB;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class CheckoutMutator
{

    public function checkout(null $_, array $args, GraphQLContext $context, ResolveInfo $info): array
    {
        /** @var \App\Models\User $user */
        $user = $context->user();
        $input = $args['input'];

        /** @var Product $product */
        $product = Product::where('is_active', true)->findOrFail($input['productId']);

        $quantity = max(1, (int) ($input['quantity'] ?? 1));

        $order = DB::transaction(function () use ($user, $input, $product, $quantity): Order {
            $order = Order::create([
                'user_id' => $user->id,
                'status' => 'queued',
                'payment_status' => 'pending',
                'total_amount' => $product->price * $quantity,
                'currency' => $product->currency,
                'shipping_address_id' => $input['shippingAddressId'] ?? null,
                'billing_address_id' => $input['billingAddressId'] ?? null,
            ]);

            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $product->id,
                'quantity' => $quantity,
                'unit_price' => $product->price,
                'currency' => $product->currency,
                'snapshot' => [
                    'title' => $product->title,
                    'sku' => $product->sku,
                    'brand' => $product->brand,
                ],
            ]);

            return $order;
        });

        dispatch(new ProcessCheckout($order->id, $product->id, $quantity))
            ->onConnection('redis');

        return [
            'order' => $order->fresh(),
            'queued' => true,
        ];
    }
}


