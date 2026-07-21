<?php

namespace App\Models;

use App\Enums\VehicleType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    use HasFactory;

    protected $fillable = [
        'brand',
        'model',
        'plate_number',
        'type',
        'status',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'type' => VehicleType::class,
        ];
    }
}
