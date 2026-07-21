<?php

namespace App\Enums;

enum InvoiceStatus: string
{
    case DRAFT = 'DRAFT';
    case SENT = 'SENT';
    case OVERDUE = 'OVERDUE';
    case PARTIALLY_PAID = 'PARTIALLY_PAID';
    case PAID = 'PAID';
    case VOID = 'VOID';

    public function label(): string
    {
        return match ($this) {
            self::DRAFT => 'Draft',
            self::SENT => 'Dikirim',
            self::OVERDUE => 'Jatuh Tempo',
            self::PARTIALLY_PAID => 'Dibayar Sebagian',
            self::PAID => 'Lunas',
            self::VOID => 'Void',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::DRAFT => 'slate',
            self::SENT => 'blue',
            self::OVERDUE => 'red',
            self::PARTIALLY_PAID => 'amber',
            self::PAID => 'emerald',
            self::VOID => 'gray',
        };
    }

    public function icon(): string
    {
        return match ($this) {
            self::DRAFT => 'file',
            self::SENT => 'send',
            self::OVERDUE => 'alert-circle',
            self::PARTIALLY_PAID => 'clock',
            self::PAID => 'check-circle',
            self::VOID => 'x-circle',
        };
    }
}
