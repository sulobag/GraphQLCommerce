<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'user_id',
        'status',
        'payment_status',
        'total_amount',
        'currency',
        'shipping_address_id',
        'billing_address_id',
        'checkout_token',
        'placed_at',
        'cancelled_at',
        'failure_reason',
        'metadata',
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'metadata' => 'array',
        'placed_at' => 'datetime',
        'cancelled_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::creating(function (self $order): void {
            $order->uuid ??= (string) Str::uuid();
            $order->checkout_token ??= (string) Str::uuid();
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function shippingAddress()
    {
        return $this->belongsTo(Address::class, 'shipping_address_id');
    }

    public function billingAddress()
    {
        return $this->belongsTo(Address::class, 'billing_address_id');
    }
}
