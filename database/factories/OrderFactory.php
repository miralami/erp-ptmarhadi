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
        $products = [
            'Beras Premium 5kg', 'Minyak Goreng 1L', 'Gula Pasir 1kg',
            'Tepung Terigu 1kg', 'Telur Ayam 1kg', 'Susu Kental Manis',
            'Kopi Bubuk 200g', 'Teh Celup 25s', 'Mie Instan Dus',
            'Kecap Manis 600ml', 'Saus Sambal 500ml', 'Mentega 200g',
            'Keju Slice 150g', 'Selai Stroberi 300g', 'Sirup 600ml',
        ];

        $statuses = [
            OrderStatus::ORDER_RECEIVED,
            OrderStatus::ORDER_RECEIVED,
            OrderStatus::DELIVERY_SCHEDULED,
            OrderStatus::DELIVERY_SCHEDULED,
            OrderStatus::DELIVERED,
            OrderStatus::DELIVERY_NOTE_RETURNED,
            OrderStatus::DELIVERY_NOTE_RETURNED,
            OrderStatus::WAITING_PO,
            OrderStatus::WAITING_PO,
            OrderStatus::WAITING_PO,
            OrderStatus::INVOICE_CREATED,
            OrderStatus::INVOICE_SENT,
            OrderStatus::UNPAID,
            OrderStatus::UNPAID,
            OrderStatus::PAID,
        ];

        $num = count(self::$usedNumbers) + 1;
        $orderNumber = 'ORD-' . now()->format('ymd') . '-' . str_pad((string)$num, 4, '0', STR_PAD_LEFT);

        return [
            'customer_id' => Customer::inRandomOrder()->first()?->id ?? 1,
            'order_number' => $orderNumber,
            'date' => fake()->dateTimeBetween('-3 months', 'now')->format('Y-m-d'),
            'status' => $statuses[array_rand($statuses)],
            'product_name' => $products[array_rand($products)],
            'quantity' => fake()->numberBetween(10, 500),
            'price' => fake()->randomFloat(2, 5000, 150000),
            'notes' => fake()->optional(0.4)->sentence(),
            'po_number' => null,
            'delivery_note_number' => null,
            'invoice_number' => null,
        ];
    }
}
