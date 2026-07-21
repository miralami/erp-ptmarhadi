<?php

namespace App\View\Components;

use App\Enums\DeliveryStatus;
use Illuminate\View\Component;
use Illuminate\View\View;

class DeliveryTimeline extends Component
{
    public array $steps;

    public function __construct(public DeliveryStatus $status)
    {
        $allSteps = [
            DeliveryStatus::SCHEDULED->value => 'Dijadwalkan',
            DeliveryStatus::IN_TRANSIT->value => 'Dalam Perjalanan',
            DeliveryStatus::DELIVERED->value => 'Terkirim',
            DeliveryStatus::PARTIALLY_DELIVERED->value => 'Sebagian Terkirim',
            DeliveryStatus::RETURNED->value => 'Dikembalikan',
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
        return view('components.timeline-delivery');
    }
}
