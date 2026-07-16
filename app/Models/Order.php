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

    public function getTotalAttribute()
    {
        return $this->quantity * $this->price;
    }

    public static function generateOrderNumber(): string
    {
        $lastOrder = self::lockForUpdate()->latest('id')->first();
        $nextId = $lastOrder ? $lastOrder->id + 1 : 1;
        return 'ORD-' . now()->format('ymd') . '-' . str_pad((string)$nextId, 4, '0', STR_PAD_LEFT);
    }
}
