<?php

namespace App\GraphQL\Mutations;

use App\Models\Order;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class OrderMutator
{

    public function cancelOrder(null $_, array $args, GraphQLContext $context, ResolveInfo $info): Order
    {
        /** @var \App\Models\User $user */
        $user = $context->user();

        $order = Order::where('user_id', $user->id)
            ->findOrFail($args['id']);

        $cancellableStatuses = ['queued', 'pending', 'processing'];
        
        if (!in_array($order->status, $cancellableStatuses)) {
            throw new \Exception("Bu sipariş iptal edilemez. Mevcut durum: {$order->status}");
        }

        $order->update([
            'status' => 'cancelled',
            'cancelled_at' => now(),
        ]);

        return $order->fresh();
    }


    public function updateOrderStatus(null $_, array $args, GraphQLContext $context, ResolveInfo $info): Order
    {
        /** @var \App\Models\User $user */
        $user = $context->user();

        $order = Order::where('user_id', $user->id)
            ->findOrFail($args['id']);

        $order->update([
            'status' => $args['status'],
        ]);

        return $order->fresh();
    }
}

