<?php

namespace App\Enums;

enum OrderCategory: string
{
    case DARAT = 'DARAT';
    case LAUT = 'LAUT';

    public function label(): string
    {
        return match ($this) {
            self::DARAT => 'Darat',
            self::LAUT => 'Laut',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::DARAT => 'emerald',
            self::LAUT => 'blue',
        };
    }

    public function icon(): string
    {
        return match ($this) {
            self::DARAT => 'truck',
            self::LAUT => 'ship',
        };
    }
}
