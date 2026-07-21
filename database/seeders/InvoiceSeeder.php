<?php

namespace Database\Seeders;

use App\Enums\InvoiceStatus;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Order;
use Illuminate\Database\Seeder;

class InvoiceSeeder extends Seeder
{
    public function run(): void
    {
        $orders = Order::with('items')->where('status', \App\Enums\OrderStatus::COMPLETED)->get();

        $additionalOrders = Order::with('items')->where('status', \App\Enums\OrderStatus::COMPLETED)
            ->take(30)->get();

        $allOrders = $orders->merge($additionalOrders)->take(80);

        $statuses = [
            InvoiceStatus::DRAFT,
            InvoiceStatus::SENT,
            InvoiceStatus::SENT,
            InvoiceStatus::SENT,
            InvoiceStatus::OVERDUE,
            InvoiceStatus::OVERDUE,
            InvoiceStatus::PARTIALLY_PAID,
            InvoiceStatus::PAID,
            InvoiceStatus::PAID,
            InvoiceStatus::PAID,
        ];

        $i = 1;
        foreach ($allOrders as $order) {
            $num = str_pad((string)$i, 4, '0', STR_PAD_LEFT);
            $subtotal = $order->items->sum(fn($item) => $item->unit * $item->price);
            if ($subtotal <= 0) {
                $subtotal = rand(500000, 5000000);
            }
            $total = $subtotal + ($subtotal * 0.011);

            $status = $statuses[array_rand($statuses)];
            $paidAmount = match ($status) {
                InvoiceStatus::PAID => $total,
                InvoiceStatus::PARTIALLY_PAID => $total * (rand(10, 90) / 100),
                default => 0,
            };

            $customerId = $order->customer_id;
            if (!Customer::find($customerId)) {
                $customerId = Customer::inRandomOrder()->first()->id;
            }

            Invoice::create([
                'invoice_number' => 'INV-' . now()->format('ymd') . '-' . $num,
                'order_id' => $order->id,
                'customer_id' => $customerId,
                'invoice_date' => now()->subDays(rand(1, 60))->format('Y-m-d'),
                'due_date' => now()->subDays(rand(-30, 30))->format('Y-m-d'),
                'subtotal' => $subtotal,
                'ppn_rate' => 1.1,
                'ppn_amount' => $subtotal * 0.011,
                'invoice_total' => $total,
                'paid_amount' => $paidAmount,
                'status' => $status,
                'notes' => rand(0, 1) ? fake()->sentence() : null,
            ]);

            $i++;
            if ($i > 80) {
                break;
            }
        }
    }
}
