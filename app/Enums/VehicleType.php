<?php

namespace App\Enums;

enum VehicleType: string
{
    case FUSO = 'FUSO';
    case TRONTON = 'TRONTON';
    case CDD = 'CDD';
    case CDE = 'CDE';
    case PICKUP = 'PICKUP';
    case WINGBOX = 'WINGBOX';
    case DUMP_TRUCK = 'DUMP_TRUCK';
    case TRAILER = 'TRAILER';
    case OTHER = 'OTHER';

    public function label(): string
    {
        return match ($this) {
            self::FUSO => 'Fuso',
            self::TRONTON => 'Tronton',
            self::CDD => 'CDD',
            self::CDE => 'CDE',
            self::PICKUP => 'Pickup',
            self::WINGBOX => 'Wingbox',
            self::DUMP_TRUCK => 'Dump Truck',
            self::TRAILER => 'Trailer',
            self::OTHER => 'Lainnya',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::FUSO => 'blue',
            self::TRONTON => 'indigo',
            self::CDD => 'emerald',
            self::CDE => 'cyan',
            self::PICKUP => 'amber',
            self::WINGBOX => 'purple',
            self::DUMP_TRUCK => 'red',
            self::TRAILER => 'orange',
            self::OTHER => 'slate',
        };
    }

    public function icon(): string
    {
        return match ($this) {
            self::FUSO => 'truck',
            self::TRONTON => 'truck',
            self::CDD => 'truck',
            self::CDE => 'truck',
            self::PICKUP => 'truck',
            self::WINGBOX => 'package',
            self::DUMP_TRUCK => 'truck',
            self::TRAILER => 'truck',
            self::OTHER => 'settings',
        };
    }
}
