<?php

namespace App\Enums;

enum PaymentMethod: string
{
    case TRANSFER = 'TRANSFER';
    case CASH = 'CASH';
    case CHEQUE = 'CHEQUE';
    case GIRO = 'GIRO';

    public function label(): string
    {
        return match ($this) {
            self::TRANSFER => 'Transfer Bank',
            self::CASH => 'Tunai',
            self::CHEQUE => 'Cek',
            self::GIRO => 'Giro',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::TRANSFER => 'blue',
            self::CASH => 'emerald',
            self::CHEQUE => 'purple',
            self::GIRO => 'amber',
        };
    }

    public function icon(): string
    {
        return match ($this) {
            self::TRANSFER => 'landmark',
            self::CASH => 'banknote',
            self::CHEQUE => 'file-text',
            self::GIRO => 'scroll-text',
        };
    }
}
