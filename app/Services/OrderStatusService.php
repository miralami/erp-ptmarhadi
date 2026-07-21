<?php

namespace App\Services;

use App\Enums\OrderStatus;
use App\Models\Order;
use InvalidArgumentException;

class OrderStatusService
{
    public function transition(Order $order, OrderStatus $newStatus): Order
    {
        $currentStatus = $order->status;

        if ($currentStatus === $newStatus) {
            return $order;
        }

        if (!$currentStatus->canTransitionTo($newStatus)) {
            throw new InvalidArgumentException(
                "Transisi status dari {$currentStatus->label()} ke {$newStatus->label()} tidak diizinkan."
            );
        }

        $order->update(['status' => $newStatus]);

        return $order->fresh();
    }
}
