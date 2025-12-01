<?php

namespace App\Jobs;

use App\Models\Inventory;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Throwable;

class ProcessCheckout implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public int $tries = 3;

    public int $timeout = 30;

    public function __construct(
        public int $orderId,
        public int $productId,
        public int $quantity
    ) {
        $this->onQueue('checkouts');
    }

    public function handle(): void
    {
        DB::transaction(function (): void {
            $order = Order::lockForUpdate()->findOrFail($this->orderId);

            if ($order->status !== 'queued') {
                return;
            }

            $inventory = Inventory::where('product_id', $this->productId)
                ->lockForUpdate()
                ->firstOrFail();

            if ($inventory->available_quantity < $this->quantity) {
                $order->update([
                    'status' => 'failed',
                    'payment_status' => 'failed',
                    'failure_reason' => 'Insufficient stock',
                ]);

                return;
            }

            $inventory->available_quantity -= $this->quantity;
            $inventory->reserved_quantity += $this->quantity;
            $inventory->save();

            $item = OrderItem::where('order_id', $order->id)
                ->where('product_id', $this->productId)
                ->firstOrFail();

            Payment::create([
                'order_id' => $order->id,
                'provider' => 'fakepay',
                'status' => 'approved',
                'reference' => 'PAY-'.uniqid(),
                'amount' => $order->total_amount,
                'currency' => $order->currency,
                'payload' => [
                    'channel' => 'graphql',
                    'description' => 'Fake payment for demo',
                ],
            ]);

            $order->update([
                'status' => 'completed',
                'payment_status' => 'paid',
                'placed_at' => now(),
            ]);
        });
    }

    public function failed(?Throwable $exception): void
    {
        Order::whereKey($this->orderId)->update([
            'status' => 'failed',
            'payment_status' => 'failed',
            'failure_reason' => $exception?->getMessage(),
        ]);
    }
}


