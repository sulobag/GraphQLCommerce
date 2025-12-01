<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;
use Laravel\Scout\Searchable;

class Product extends Model
{
    use HasFactory;
    use Searchable;

    protected $fillable = [
        'uuid',
        'sku',
        'slug',
        'title',
        'brand',
        'category',
        'description',
        'price',
        'currency',
        'is_active',
        'metadata',
        'search_tags',
        'primary_image_url',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'metadata' => 'array',
        'search_tags' => 'array',
        'price' => 'decimal:2',
    ];

    protected static function booted(): void
    {
        static::creating(function (self $product): void {
            $product->uuid ??= (string) Str::uuid();
            $product->slug ??= Str::slug($product->title.'-'.Str::random(6));
        });
    }

    public function inventory(): HasOne
    {
        return $this->hasOne(Inventory::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function toSearchableArray(): array
    {
        return [
            'uuid' => $this->uuid,
            'title' => $this->title,
            'brand' => $this->brand,
            'category' => $this->category,
            'description' => $this->description,
            'price' => $this->price,
            'currency' => $this->currency,
            'tags' => $this->search_tags ?? [],
            'is_active' => $this->is_active,
        ];
    }
}
