<?php

namespace App\Enums;

enum OrderStatus: string
{
    case ORDER_RECEIVED = 'ORDER_RECEIVED';
    case DELIVERY_SCHEDULED = 'DELIVERY_SCHEDULED';
    case DELIVERED = 'DELIVERED';
    case DELIVERY_NOTE_RETURNED = 'DELIVERY_NOTE_RETURNED';
    case WAITING_PO = 'WAITING_PO';
    case INVOICE_CREATED = 'INVOICE_CREATED';
    case INVOICE_SENT = 'INVOICE_SENT';
    case UNPAID = 'UNPAID';
    case PAID = 'PAID';

    public function label(): string
    {
        return match ($this) {
            self::ORDER_RECEIVED => 'Order Baru',
            self::DELIVERY_SCHEDULED => 'Pengiriman Dijadwalkan',
            self::DELIVERED => 'Barang Dikirim',
            self::DELIVERY_NOTE_RETURNED => 'Surat Jalan Kembali',
            self::WAITING_PO => 'Menunggu PO',
            self::INVOICE_CREATED => 'Invoice Dibuat',
            self::INVOICE_SENT => 'Invoice Dikirim',
            self::UNPAID => 'Belum Bayar',
            self::PAID => 'Lunas',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::ORDER_RECEIVED => 'blue',
            self::DELIVERY_SCHEDULED => 'indigo',
            self::DELIVERED => 'cyan',
            self::DELIVERY_NOTE_RETURNED => 'purple',
            self::WAITING_PO => 'amber',
            self::INVOICE_CREATED => 'teal',
            self::INVOICE_SENT => 'sky',
            self::UNPAID => 'red',
            self::PAID => 'emerald',
        };
    }

    public function icon(): string
    {
        return match ($this) {
            self::ORDER_RECEIVED => 'package',
            self::DELIVERY_SCHEDULED => 'truck',
            self::DELIVERED => 'check-circle',
            self::DELIVERY_NOTE_RETURNED => 'file-text',
            self::WAITING_PO => 'clock',
            self::INVOICE_CREATED => 'file-invoice',
            self::INVOICE_SENT => 'send',
            self::UNPAID => 'alert-circle',
            self::PAID => 'check-circle-2',
        };
    }
}
