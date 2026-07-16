<?php

namespace App\Models;

use App\Enums\OrderStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Order extends Model
{
    protected $fillable = [
        'customer_id',
        'order_number',
        'date',
        'status',
        'product_name',
        'quantity',
        'price',
        'notes',
        'po_number',
        'delivery_note_number',
        'invoice_number',
    ];

    protected function casts(): array
    {
        return [
            'date' => 'date',
            'status' => OrderStatus::class,
            'price' => 'decimal:2',
        ];
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function getTotalAttribute(): string
    {
        return number_format($this->quantity * $this->price, 2);
    }
}
