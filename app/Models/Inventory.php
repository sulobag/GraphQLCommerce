<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'available_quantity',
        'reserved_quantity',
        'safety_threshold',
    ];

    protected $casts = [
        'available_quantity' => 'integer',
        'reserved_quantity' => 'integer',
        'safety_threshold' => 'integer',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
