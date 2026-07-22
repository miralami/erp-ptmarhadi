<?php

namespace App\Enums;

enum OrderStatus: string
{
    case ORDER_RECEIVED = 'ORDER_RECEIVED';
    case PERJALANAN_MUAT = 'PERJALANAN_MUAT';
    case PERJALANAN_BONGKAR = 'PERJALANAN_BONGKAR';
    case COMPLETED = 'COMPLETED';
    case CANCELLED = 'CANCELLED';

    public function label(): string
    {
        return match ($this) {
            self::ORDER_RECEIVED => 'Order Baru',
            self::PERJALANAN_MUAT => 'Perjalanan Muat',
            self::PERJALANAN_BONGKAR => 'Perjalanan Bongkar',
            self::COMPLETED => 'Selesai',
            self::CANCELLED => 'Dibatalkan',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::ORDER_RECEIVED => 'blue',
            self::PERJALANAN_MUAT => 'amber',
            self::PERJALANAN_BONGKAR => 'orange',
            self::COMPLETED => 'emerald',
            self::CANCELLED => 'red',
        };
    }

    public function icon(): string
    {
        return match ($this) {
            self::ORDER_RECEIVED => 'package',
            self::PERJALANAN_MUAT => 'truck',
            self::PERJALANAN_BONGKAR => 'map-pin',
            self::COMPLETED => 'check-circle',
            self::CANCELLED => 'x-circle',
        };
    }

    public static function allowedTransitions(): array
    {
        return [
            self::ORDER_RECEIVED->value => [self::PERJALANAN_MUAT, self::CANCELLED],
            self::PERJALANAN_MUAT->value => [self::PERJALANAN_BONGKAR, self::CANCELLED],
            self::PERJALANAN_BONGKAR->value => [self::COMPLETED, self::CANCELLED],
            self::COMPLETED->value => [],
            self::CANCELLED->value => [],
        ];
    }

    public function canTransitionTo(self $target): bool
    {
        $allowed = self::allowedTransitions()[$this->value] ?? [];
        return in_array($target, $allowed);
    }
}
