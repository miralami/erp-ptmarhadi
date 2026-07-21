<?php

namespace App\Enums;

enum VehicleSource: string
{
    case OWNED = 'OWNED';
    case RENTED = 'RENTED';

    public function label(): string
    {
        return match ($this) {
            self::OWNED => 'Marhadi',
            self::RENTED => 'Sewa',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::OWNED => 'emerald',
            self::RENTED => 'amber',
        };
    }

    public function icon(): string
    {
        return match ($this) {
            self::OWNED => 'building-2',
            self::RENTED => 'truck',
        };
    }
}
