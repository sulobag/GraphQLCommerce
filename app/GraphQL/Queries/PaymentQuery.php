<?php

namespace App\GraphQL\Queries;

use App\Models\Order;
use App\Models\Payment;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class PaymentQuery
{

    public function orderPayments(null $_, array $args, GraphQLContext $context, ResolveInfo $info)
    {
        /** @var \App\Models\User $user */
        $user = $context->user();

        $order = Order::where('user_id', $user->id)
            ->findOrFail($args['orderId']);

        return $order->payments()->orderBy('created_at', 'desc')->get();
    }


    public function payment(null $_, array $args, GraphQLContext $context, ResolveInfo $info): Payment
    {
        /** @var \App\Models\User $user */
        $user = $context->user();

        $payment = Payment::findOrFail($args['id']);
        
        if ($payment->order->user_id !== $user->id) {
            throw new \Exception('Bu ödemeye erişim yetkiniz yok.');
        }

        return $payment;
    }
}

