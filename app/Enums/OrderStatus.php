<?php

namespace App\Enums;

enum OrderStatus: string
{
    case ORDER_RECEIVED = 'ORDER_RECEIVED';
    case SCHEDULED = 'SCHEDULED';
    case IN_TRANSIT = 'IN_TRANSIT';
    case COMPLETED = 'COMPLETED';
    case CANCELLED = 'CANCELLED';

    public function label(): string
    {
        return match ($this) {
            self::ORDER_RECEIVED => 'Order Baru',
            self::SCHEDULED => 'Dijadwalkan',
            self::IN_TRANSIT => 'Dalam Perjalanan',
            self::COMPLETED => 'Selesai',
            self::CANCELLED => 'Dibatalkan',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::ORDER_RECEIVED => 'blue',
            self::SCHEDULED => 'indigo',
            self::IN_TRANSIT => 'amber',
            self::COMPLETED => 'emerald',
            self::CANCELLED => 'red',
        };
    }

    public function icon(): string
    {
        return match ($this) {
            self::ORDER_RECEIVED => 'package',
            self::SCHEDULED => 'calendar',
            self::IN_TRANSIT => 'truck',
            self::COMPLETED => 'check-circle',
            self::CANCELLED => 'x-circle',
        };
    }

    public static function allowedTransitions(): array
    {
        return [
            self::ORDER_RECEIVED->value => [self::SCHEDULED, self::CANCELLED],
            self::SCHEDULED->value => [self::IN_TRANSIT, self::CANCELLED],
            self::IN_TRANSIT->value => [self::COMPLETED, self::CANCELLED],
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
