<?php

namespace App\Models;

use App\Enums\DeliveryStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Delivery extends Model
{
    use HasFactory;

    protected $fillable = [
        'delivery_number',
        'order_id',
        'vehicle_id',
        'vehicle_plate_manual',
        'vehicle_type_manual',
        'delivery_date',
        'driver_name',
        'uang_jalan',
        'status',
        'photo_muat',
        'photo_bongkar',
        'photo_surat_jalan',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'delivery_date' => 'date',
            'status' => DeliveryStatus::class,
            'uang_jalan' => 'decimal:2',
            'photo_muat' => 'array',
            'photo_bongkar' => 'array',
            'photo_surat_jalan' => 'array',
        ];
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function expenses(): HasMany
    {
        return $this->hasMany(DeliveryExpense::class);
    }
}
