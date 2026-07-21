<?php

namespace App\Services;

use App\Models\Invoice;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;

class PaymentService
{
    public function __construct(
        private DocumentNumberService $documentNumber,
        private InvoiceService $invoiceService,
        private ActivityLogService $activityLog,
    ) {}

    public function recordPayment(Invoice $invoice, array $data): Payment
    {
        return DB::transaction(function () use ($invoice, $data) {
            $paymentNumber = $this->documentNumber->generate('PAY', 'payments');

            $payment = Payment::create([
                'payment_number' => $paymentNumber,
                'invoice_id' => $invoice->id,
                'payment_date' => $data['payment_date'],
                'amount' => $data['amount'],
                'payment_method' => $data['payment_method'],
                'reference_number' => $data['reference_number'] ?? null,
                'notes' => $data['notes'] ?? null,
            ]);

            $this->invoiceService->updateStatusFromPayments($invoice);

            $this->activityLog->log(
                module: 'payment',
                recordId: $payment->id,
                action: 'recorded',
                description: "Pembayaran {$paymentNumber} sebesar Rp " . number_format($data['amount'], 0, ',', '.') . " untuk Invoice {$invoice->invoice_number}",
            );

            return $payment;
        });
    }
}
