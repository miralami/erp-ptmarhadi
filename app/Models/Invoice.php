<?php

namespace App\Models;

use App\Enums\InvoiceStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Invoice extends Model
{
    use HasFactory;
    protected $fillable = [
        'invoice_number',
        'order_id',
        'customer_id',
        'customer_po_number',
        'customer_spb_number',
        'invoice_date',
        'due_date',
        'subtotal',
        'ppn_rate',
        'ppn_amount',
        'invoice_total',
        'paid_amount',
        'status',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'invoice_date' => 'date',
            'due_date' => 'date',
            'subtotal' => 'decimal:2',
            'ppn_rate' => 'decimal:2',
            'ppn_amount' => 'decimal:2',
            'invoice_total' => 'decimal:2',
            'paid_amount' => 'decimal:2',
            'status' => InvoiceStatus::class,
        ];
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function getRemainingAttribute(): float
    {
        return max(0, $this->invoice_total - $this->paid_amount);
    }

    public function getIsOverdueAttribute(): bool
    {
        return $this->due_date->isPast()
            && $this->status !== InvoiceStatus::PAID
            && $this->status !== InvoiceStatus::VOID;
    }
}
