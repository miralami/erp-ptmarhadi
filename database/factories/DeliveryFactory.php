<?php

namespace Database\Factories;

use App\Enums\DeliveryStatus;
use App\Models\Delivery;
use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;

class DeliveryFactory extends Factory
{
    protected $model = Delivery::class;

    protected static array $usedNumbers = [];

    public function definition(): array
    {
        $num = count(self::$usedNumbers) + 1;
        $deliveryNumber = 'DEL-' . now()->format('ymd') . '-' . str_pad((string)$num, 4, '0', STR_PAD_LEFT);
        self::$usedNumbers[] = $deliveryNumber;

        return [
            'delivery_number' => $deliveryNumber,
            'order_id' => Order::factory(),
            'delivery_date' => fake()->dateTimeBetween('-2 months', 'now'),
            'driver_name' => fake()->name(),
            'uang_jalan' => fake()->randomFloat(2, 100000, 500000),
            'status' => fake()->randomElement(DeliveryStatus::cases()),
            'notes' => fake()->optional(0.3)->sentence(),
        ];
    }
}
