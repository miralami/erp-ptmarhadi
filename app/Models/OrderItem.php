<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'product_name',
        'unit',
        'price',
        'kubikasi',
        'max_slot',
        'police_fee',
        'threshold_exceeded',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'kubikasi' => 'decimal:2',
            'police_fee' => 'decimal:2',
            'threshold_exceeded' => 'boolean',
        ];
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function getSubtotalAttribute(): float
    {
        return $this->unit * $this->price;
    }
}
