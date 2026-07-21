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
            OrderStatus::SCHEDULED->value => 'Pengiriman Dijadwalkan',
            OrderStatus::IN_TRANSIT->value => 'Dalam Perjalanan',
            OrderStatus::COMPLETED->value => 'Selesai',
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
