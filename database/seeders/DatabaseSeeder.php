<?php

namespace Database\Seeders;

use App\Models\Address;
use App\Models\Inventory;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        DB::transaction(function (): void {
            $user = User::factory()->create([
                'name' => 'Demo Customer',
                'email' => 'demo@commerce.test',
                'password' => Hash::make('password'),
                'phone' => '+905551112233',
            ]);

            Address::factory()->count(2)->create([
                'user_id' => $user->id,
                'is_primary' => true,
                'type' => 'shipping',
            ]);

            Product::factory(20)
                ->create()
                ->each(fn (Product $product) => Inventory::factory()->create([
                    'product_id' => $product->id,
                    'available_quantity' => random_int(10, 75),
                ]));

            $product = Product::first();

            if ($product) {
                $order = Order::factory()->create([
                    'user_id' => $user->id,
                    'total_amount' => $product->price,
                    'currency' => $product->currency,
                ]);

                OrderItem::factory()->create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'quantity' => 1,
                    'unit_price' => $product->price,
                    'currency' => $product->currency,
        ]);
            }
        });
    }
}
