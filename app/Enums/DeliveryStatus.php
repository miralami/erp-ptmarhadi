<?php

namespace App\Enums;

enum DeliveryStatus: string
{
    case SCHEDULED = 'SCHEDULED';
    case IN_TRANSIT = 'IN_TRANSIT';
    case DELIVERED = 'DELIVERED';
    case PARTIALLY_DELIVERED = 'PARTIALLY_DELIVERED';
    case RETURNED = 'RETURNED';

    public function label(): string
    {
        return match ($this) {
            self::SCHEDULED => 'Dijadwalkan',
            self::IN_TRANSIT => 'Dalam Perjalanan',
            self::DELIVERED => 'Terkirim',
            self::PARTIALLY_DELIVERED => 'Sebagian Terkirim',
            self::RETURNED => 'Dikembalikan',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::SCHEDULED => 'indigo',
            self::IN_TRANSIT => 'amber',
            self::DELIVERED => 'emerald',
            self::PARTIALLY_DELIVERED => 'orange',
            self::RETURNED => 'red',
        };
    }

    public function icon(): string
    {
        return match ($this) {
            self::SCHEDULED => 'calendar',
            self::IN_TRANSIT => 'truck',
            self::DELIVERED => 'check-circle',
            self::PARTIALLY_DELIVERED => 'alert-triangle',
            self::RETURNED => 'rotate-ccw',
        };
    }
}
