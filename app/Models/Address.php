<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Address extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'user_id',
        'label',
        'contact_name',
        'line1',
        'line2',
        'city',
        'state',
        'postal_code',
        'country',
        'phone',
        'type',
        'is_primary',
        'metadata',
    ];

    protected $casts = [
        'is_primary' => 'boolean',
        'metadata' => 'array',
    ];

    protected static function booted(): void
    {
        static::creating(function (self $address): void {
            $address->uuid ??= (string) Str::uuid();
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
