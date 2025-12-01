<?php

namespace App\GraphQL\Mutations;

use App\Models\Order;
use App\Models\Payment;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class PaymentMutator
{

    public function createPayment(null $_, array $args, GraphQLContext $context, ResolveInfo $info): Payment
    {
        /** @var \App\Models\User $user */
        $user = $context->user();
        $input = $args['input'];

        $order = Order::where('user_id', $user->id)
            ->findOrFail($input['orderId']);

        $payment = Payment::create([
            'order_id' => $order->id,
            'provider' => $input['provider'],
            'status' => $input['status'] ?? 'pending',
            'reference' => $input['reference'],
            'amount' => $input['amount'],
            'currency' => $input['currency'] ?? $order->currency,
            'payload' => $input['payload'] ?? null,
        ]);

        if ($payment->status === 'completed') {
            $order->update(['payment_status' => 'paid']);
        } elseif ($payment->status === 'failed') {
            $order->update(['payment_status' => 'failed']);
        }

        return $payment;
    }


    public function updatePaymentStatus(null $_, array $args, GraphQLContext $context, ResolveInfo $info): Payment
    {
        /** @var \App\Models\User $user */
        $user = $context->user();

        $payment = Payment::findOrFail($args['id']);
        
        // Payment'ın order'ı kullanıcıya ait mi kontrol et
        if ($payment->order->user_id !== $user->id) {
            throw new \Exception('Bu ödemeye erişim yetkiniz yok.');
        }

        $payment->update([
            'status' => $args['status'],
        ]);

        $order = $payment->order;
        if ($args['status'] === 'completed') {
            $order->update(['payment_status' => 'paid']);
        } elseif ($args['status'] === 'failed') {
            $order->update(['payment_status' => 'failed']);
        }

        return $payment->fresh();
    }
}

