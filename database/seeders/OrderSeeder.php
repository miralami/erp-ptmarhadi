<?php

namespace Database\Seeders;

use App\Enums\OrderStatus;
use App\Models\Customer;
use App\Models\Order;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        $customers = Customer::all();
        $products = [
            ['name' => 'Beras Premium 5kg', 'price' => 75000],
            ['name' => 'Minyak Goreng 1L', 'price' => 18000],
            ['name' => 'Gula Pasir 1kg', 'price' => 16000],
            ['name' => 'Tepung Terigu 1kg', 'price' => 14000],
            ['name' => 'Telur Ayam 1kg', 'price' => 28000],
            ['name' => 'Susu Kental Manis', 'price' => 12000],
            ['name' => 'Kopi Bubuk 200g', 'price' => 25000],
            ['name' => 'Teh Celup 25s', 'price' => 8500],
            ['name' => 'Mie Instan Dus', 'price' => 95000],
            ['name' => 'Kecap Manis 600ml', 'price' => 22000],
            ['name' => 'Saus Sambal 500ml', 'price' => 19500],
            ['name' => 'Mentega 200g', 'price' => 32000],
            ['name' => 'Keju Slice 150g', 'price' => 45000],
            ['name' => 'Selai Stroberi 300g', 'price' => 38000],
            ['name' => 'Sirup 600ml', 'price' => 29000],
        ];

        $statuses = [
            OrderStatus::ORDER_RECEIVED,
            OrderStatus::ORDER_RECEIVED,
            OrderStatus::SCHEDULED,
            OrderStatus::SCHEDULED,
            OrderStatus::IN_TRANSIT,
            OrderStatus::IN_TRANSIT,
            OrderStatus::COMPLETED,
            OrderStatus::COMPLETED,
            OrderStatus::COMPLETED,
            OrderStatus::CANCELLED,
        ];

        for ($i = 1; $i <= 100; $i++) {
            $customer = $customers->random();
            $num = str_pad((string)$i, 4, '0', STR_PAD_LEFT);
            $status = $statuses[array_rand($statuses)];

            $order = Order::create([
                'customer_id' => $customer->id,
                'order_number' => 'ORD-' . now()->format('ymd') . '-' . $num,
                'order_date' => now()->subDays(rand(1, 90))->format('Y-m-d'),
                'status' => $status,
                'notes' => rand(0, 1) ? 'Catatan: ' . fake()->sentence() : null,
            ]);

            $numItems = rand(1, 3);
            for ($j = 0; $j < $numItems; $j++) {
                $product = $products[array_rand($products)];
                $order->items()->create([
                    'product_name' => $product['name'],
                    'unit' => rand(5, 200),
                    'price' => $product['price'],
                ]);
            }
        }
    }
}
