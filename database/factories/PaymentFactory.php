<?php

namespace Database\Factories;

use App\Enums\PaymentMethod;
use App\Models\Invoice;
use App\Models\Payment;
use Illuminate\Database\Eloquent\Factories\Factory;

class PaymentFactory extends Factory
{
    protected $model = Payment::class;

    protected static array $usedNumbers = [];

    public function definition(): array
    {
        $num = count(self::$usedNumbers) + 1;
        $paymentNumber = 'PAY-' . now()->format('ymd') . '-' . str_pad((string)$num, 4, '0', STR_PAD_LEFT);
        self::$usedNumbers[] = $paymentNumber;

        $invoice = Invoice::factory()->create();
        $remaining = $invoice->invoice_total - $invoice->paid_amount;
        $amount = $remaining > 0 ? min($remaining, fake()->randomFloat(2, 50000, $remaining)) : fake()->randomFloat(2, 50000, 1000000);

        return [
            'payment_number' => $paymentNumber,
            'invoice_id' => $invoice->id,
            'payment_date' => fake()->dateTimeBetween('-1 month', 'now'),
            'amount' => $amount > 0 ? $amount : fake()->randomFloat(2, 50000, 1000000),
            'payment_method' => fake()->randomElement(PaymentMethod::cases()),
            'reference_number' => fake()->optional(0.7)->numerify('TRF-########'),
            'notes' => fake()->optional(0.3)->sentence(),
        ];
    }
}
