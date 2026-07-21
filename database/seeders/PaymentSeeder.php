<?php

namespace Database\Seeders;

use App\Enums\PaymentMethod;
use App\Models\Invoice;
use App\Models\Payment;
use Illuminate\Database\Seeder;

class PaymentSeeder extends Seeder
{
    public function run(): void
    {
        $methods = PaymentMethod::cases();

        $paidInvoices = Invoice::where('status', \App\Enums\InvoiceStatus::PAID)->get();
        foreach ($paidInvoices as $invoice) {
            Payment::create([
                'payment_number' => 'PAY-' . now()->format('ymd') . '-' . str_pad((string)$invoice->id, 4, '0', STR_PAD_LEFT),
                'invoice_id' => $invoice->id,
                'payment_date' => $invoice->invoice_date->addDays(rand(1, 30))->format('Y-m-d'),
                'amount' => $invoice->invoice_total,
                'payment_method' => $methods[array_rand($methods)]->value,
                'reference_number' => 'TRF-' . fake()->numerify('########'),
                'notes' => 'Pembayaran lunas',
            ]);
        }

        $partialInvoices = Invoice::where('status', \App\Enums\InvoiceStatus::PARTIALLY_PAID)->get();
        foreach ($partialInvoices as $invoice) {
            $remaining = $invoice->invoice_total;
            $paymentCount = rand(1, 3);
            for ($p = 1; $p <= $paymentCount; $p++) {
                $amount = $p < $paymentCount
                    ? round($remaining * rand(10, 50) / 100, 2)
                    : round($remaining, 2);

                Payment::create([
                    'payment_number' => 'PAY-' . now()->format('ymd') . '-' . str_pad((string)$invoice->id . '-' . $p, 4, '0', STR_PAD_LEFT),
                    'invoice_id' => $invoice->id,
                    'payment_date' => $invoice->invoice_date->addDays(rand(1, 45))->format('Y-m-d'),
                    'amount' => $amount,
                    'payment_method' => $methods[array_rand($methods)]->value,
                    'reference_number' => $amount > 1000000 ? 'TRF-' . fake()->numerify('########') : null,
                    'notes' => $p === 1 ? 'Pembayaran ke-' . $p : 'Pembayaran lanjutan',
                ]);
                $remaining -= $amount;
            }
            $invoice->update(['paid_amount' => $invoice->invoice_total - $remaining]);
        }

        $sentInvoices = Invoice::whereIn('status', [
            \App\Enums\InvoiceStatus::SENT,
            \App\Enums\InvoiceStatus::OVERDUE,
        ])->take(20)->get();

        foreach ($sentInvoices as $invoice) {
            if (rand(0, 1) === 0) {
                $amount = $invoice->invoice_total;
                Payment::create([
                    'payment_number' => 'PAY-' . now()->format('ymd') . '-' . str_pad((string)$invoice->id . '-late', 4, '0', STR_PAD_LEFT),
                    'invoice_id' => $invoice->id,
                    'payment_date' => now()->subDays(rand(1, 5))->format('Y-m-d'),
                    'amount' => $amount,
                    'payment_method' => $methods[array_rand($methods)]->value,
                    'reference_number' => 'TRF-' . fake()->numerify('########'),
                    'notes' => 'Pembayaran via transfer',
                ]);
                $invoice->update([
                    'paid_amount' => $amount,
                    'status' => $amount >= $invoice->invoice_total ? \App\Enums\InvoiceStatus::PAID : \App\Enums\InvoiceStatus::PARTIALLY_PAID,
                ]);
            }
        }
    }
}
