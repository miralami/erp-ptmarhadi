<?php

namespace Database\Seeders;

use App\Enums\DeliveryStatus;
use App\Models\Delivery;
use App\Models\Order;
use Illuminate\Database\Seeder;

class DeliverySeeder extends Seeder
{
    public function run(): void
    {
        $orders = Order::whereIn('status', [
            \App\Enums\OrderStatus::ORDER_RECEIVED,
            \App\Enums\OrderStatus::SCHEDULED,
            \App\Enums\OrderStatus::IN_TRANSIT,
            \App\Enums\OrderStatus::COMPLETED,
            \App\Enums\OrderStatus::CANCELLED,
        ])->get();

        $drivers = ['Suparman', 'Slamet', 'Agus', 'Joko', 'Hendra', 'Bambang', 'Eko', 'Rudi'];
        $statuses = DeliveryStatus::cases();

        for ($i = 1; $i <= 150; $i++) {
            $order = $orders->random();
            $num = str_pad((string)$i, 4, '0', STR_PAD_LEFT);

            Delivery::create([
                'delivery_number' => 'DEL-' . now()->format('ymd') . '-' . $num,
                'order_id' => $order->id,
                'delivery_date' => now()->subDays(rand(1, 60))->format('Y-m-d'),
                'driver_name' => $drivers[array_rand($drivers)],
                'uang_jalan' => rand(100000, 500000),
                'status' => $statuses[array_rand($statuses)],
                'notes' => rand(0, 1) ? fake()->sentence() : null,
            ]);
        }
    }
}
