<?php

namespace Database\Factories;

use App\Enums\InvoiceStatus;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;

class InvoiceFactory extends Factory
{
    protected $model = Invoice::class;

    protected static array $usedNumbers = [];

    public function definition(): array
    {
        $num = count(self::$usedNumbers) + 1;
        $invoiceNumber = 'INV-' . now()->format('ymd') . '-' . str_pad((string)$num, 4, '0', STR_PAD_LEFT);
        self::$usedNumbers[] = $invoiceNumber;

        $order = Order::factory()->create();
        $order->items()->createMany([
            ['product_name' => 'Sample Item', 'unit' => 10, 'price' => 50000],
        ]);
        $order->load('items');
        $subtotal = $order->items->sum(fn($item) => $item->unit * $item->price);
        $total = $subtotal + ($subtotal * 0.011);

        $status = fake()->randomElement(InvoiceStatus::cases());
        $paidAmount = match ($status) {
            InvoiceStatus::PAID => $total,
            InvoiceStatus::PARTIALLY_PAID => $total * fake()->randomFloat(2, 0.1, 0.9),
            default => 0,
        };

        return [
            'invoice_number' => $invoiceNumber,
            'order_id' => $order->id,
            'customer_id' => $order->customer_id,
            'invoice_date' => fake()->dateTimeBetween('-2 months', 'now'),
            'due_date' => fake()->dateTimeBetween('-1 month', '+1 month'),
            'subtotal' => $subtotal,
            'ppn_rate' => 1.1,
            'ppn_amount' => $subtotal * 0.011,
            'invoice_total' => $total,
            'paid_amount' => $paidAmount,
            'status' => $status,
            'notes' => fake()->optional(0.3)->sentence(),
        ];
    }
}
