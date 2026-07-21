<?php

namespace App\Models;

use App\Enums\OrderCategory;
use App\Enums\OrderStatus;
use App\Enums\VehicleSource;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'order_number',
        'order_date',
        'received_by',
        'origin_company',
        'origin_city',
        'destination_city',
        'category',
        'vehicle_source',
        'customer_po_number',
        'customer_spb_number',
        'status',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'order_date' => 'date',
            'status' => OrderStatus::class,
            'category' => OrderCategory::class,
            'vehicle_source' => VehicleSource::class,
        ];
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function delivery(): HasOne
    {
        return $this->hasOne(Delivery::class);
    }

    public function invoice(): HasOne
    {
        return $this->hasOne(Invoice::class);
    }

    public function getTotalAttribute(): float
    {
        return $this->items->sum(fn($item) => $item->unit * $item->price);
    }
}
