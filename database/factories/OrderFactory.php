<?php

namespace Database\Factories;

use App\Enums\OrderStatus;
use App\Models\Customer;
use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    protected $model = Order::class;

    protected static array $usedNumbers = [];

    public function definition(): array
    {
        $num = count(self::$usedNumbers) + 1;
        $orderNumber = 'ORD-' . now()->format('ymd') . '-' . str_pad((string)$num, 4, '0', STR_PAD_LEFT);
        self::$usedNumbers[] = $orderNumber;

        return [
            'customer_id' => Customer::factory(),
            'order_number' => $orderNumber,
            'order_date' => fake()->dateTimeBetween('-3 months', 'now')->format('Y-m-d'),
            'status' => fake()->randomElement(OrderStatus::cases()),
            'notes' => fake()->optional(0.4)->sentence(),
        ];
    }
}
