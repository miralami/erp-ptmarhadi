<?php

namespace App\View\Components;

use App\Enums\OrderStatus;
use Illuminate\View\Component;
use Illuminate\View\View;

class Timeline extends Component
{
    public array $steps;

    public function __construct(public OrderStatus $status)
    {
        $allSteps = [
            OrderStatus::ORDER_RECEIVED->value => 'Order Diterima',
            OrderStatus::DELIVERY_SCHEDULED->value => 'Pengiriman Dijadwalkan',
            OrderStatus::DELIVERED->value => 'Barang Dikirim',
            OrderStatus::DELIVERY_NOTE_RETURNED->value => 'Surat Jalan Kembali',
            OrderStatus::WAITING_PO->value => 'Menunggu PO Customer',
            OrderStatus::INVOICE_CREATED->value => 'Invoice Dibuat',
            OrderStatus::INVOICE_SENT->value => 'Invoice Dikirim',
            OrderStatus::UNPAID->value => 'Menunggu Pembayaran',
            OrderStatus::PAID->value => 'Pembayaran Diterima',
        ];

        $statusOrder = array_keys($allSteps);
        $currentIndex = array_search($this->status->value, $statusOrder);

        $totalSteps = count($allSteps);
        $this->steps = [];
        foreach ($allSteps as $key => $label) {
            $stepIndex = array_search($key, $statusOrder);
            if ($stepIndex < $currentIndex || ($stepIndex === $currentIndex && $currentIndex === $totalSteps - 1)) {
                $stepStatus = 'completed';
            } elseif ($stepIndex === $currentIndex) {
                $stepStatus = 'active';
            } else {
                $stepStatus = 'pending';
            }
            $this->steps[] = [
                'label' => $label,
                'status' => $stepStatus,
            ];
        }
    }

    public function render(): View
    {
        return view('components.timeline');
    }
}
