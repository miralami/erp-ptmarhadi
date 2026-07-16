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

        $orders = [
            ['status' => OrderStatus::ORDER_RECEIVED, 'product' => 'Beras Premium 5kg', 'qty' => 200, 'price' => 75000],
            ['status' => OrderStatus::ORDER_RECEIVED, 'product' => 'Minyak Goreng 1L', 'qty' => 150, 'price' => 18000],
            ['status' => OrderStatus::DELIVERY_SCHEDULED, 'product' => 'Gula Pasir 1kg', 'qty' => 300, 'price' => 16000],
            ['status' => OrderStatus::DELIVERY_SCHEDULED, 'product' => 'Tepung Terigu 1kg', 'qty' => 250, 'price' => 14000],
            ['status' => OrderStatus::DELIVERED, 'product' => 'Telur Ayam 1kg', 'qty' => 100, 'price' => 28000],
            ['status' => OrderStatus::DELIVERY_NOTE_RETURNED, 'product' => 'Mie Instan Dus', 'qty' => 75, 'price' => 95000],
            ['status' => OrderStatus::DELIVERY_NOTE_RETURNED, 'product' => 'Kopi Bubuk 200g', 'qty' => 80, 'price' => 25000],
            ['status' => OrderStatus::WAITING_PO, 'product' => 'Susu Kental Manis', 'qty' => 200, 'price' => 12000],
            ['status' => OrderStatus::WAITING_PO, 'product' => 'Teh Celup 25s', 'qty' => 500, 'price' => 8500],
            ['status' => OrderStatus::WAITING_PO, 'product' => 'Kecap Manis 600ml', 'qty' => 120, 'price' => 22000],
            ['status' => OrderStatus::INVOICE_CREATED, 'product' => 'Saus Sambal 500ml', 'qty' => 90, 'price' => 19500],
            ['status' => OrderStatus::INVOICE_SENT, 'product' => 'Mentega 200g', 'qty' => 60, 'price' => 32000],
            ['status' => OrderStatus::UNPAID, 'product' => 'Keju Slice 150g', 'qty' => 45, 'price' => 45000],
            ['status' => OrderStatus::UNPAID, 'product' => 'Selai Stroberi 300g', 'qty' => 55, 'price' => 38000],
            ['status' => OrderStatus::PAID, 'product' => 'Sirup 600ml', 'qty' => 70, 'price' => 29000],
        ];

        foreach ($orders as $i => $item) {
            $customer = $customers->random();
            $num = str_pad((string)($i + 1), 4, '0', STR_PAD_LEFT);

            $data = [
                'customer_id' => $customer->id,
                'order_number' => 'ORD-' . now()->format('ymd') . '-' . $num,
                'date' => now()->subDays(rand(1, 90))->format('Y-m-d'),
                'status' => $item['status'],
                'product_name' => $item['product'],
                'quantity' => $item['qty'],
                'price' => $item['price'],
                'notes' => rand(0, 1) ? 'Catatan: ' . fake()->sentence() : null,
            ];

            if (in_array($item['status'], [
                OrderStatus::DELIVERY_SCHEDULED, OrderStatus::DELIVERED,
                OrderStatus::DELIVERY_NOTE_RETURNED, OrderStatus::WAITING_PO,
                OrderStatus::INVOICE_CREATED, OrderStatus::INVOICE_SENT,
                OrderStatus::UNPAID, OrderStatus::PAID,
            ])) {
                $data['delivery_note_number'] = 'SJ-' . now()->format('ymd') . '-' . $num;
            }

            if (in_array($item['status'], [
                OrderStatus::WAITING_PO, OrderStatus::INVOICE_CREATED,
                OrderStatus::INVOICE_SENT, OrderStatus::UNPAID, OrderStatus::PAID,
            ])) {
                $data['po_number'] = 'PO-' . now()->format('ymd') . '-' . $num;
            }

            if (in_array($item['status'], [
                OrderStatus::INVOICE_CREATED, OrderStatus::INVOICE_SENT,
                OrderStatus::UNPAID, OrderStatus::PAID,
            ])) {
                $data['invoice_number'] = 'INV-' . now()->format('ymd') . '-' . $num;
            }

            Order::create($data);
        }
    }
}
