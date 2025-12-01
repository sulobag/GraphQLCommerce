<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'user_id',
        'total_amount',
        'currency',
        'metadata',
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'metadata' => 'array',
    ];

    protected static function booted(): void
    {
        static::creating(function (self $cart): void {
            $cart->uuid ??= (string) Str::uuid();
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(CartItem::class);
    }


    public function calculateTotal(): void
    {
        $this->total_amount = $this->items()
            ->sum(DB::raw('quantity * unit_price'));
        $this->save();
    }
}

