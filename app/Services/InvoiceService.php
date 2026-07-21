<?php

namespace App\Services;

use App\Enums\InvoiceStatus;
use App\Models\Invoice;
use App\Models\Order;
use Illuminate\Support\Facades\DB;

class InvoiceService
{
    public function __construct(
        private DocumentNumberService $documentNumber,
        private ActivityLogService $activityLog,
    ) {}

    public function createFromOrder(Order $order, array $extraData = []): Invoice
    {
        return DB::transaction(function () use ($order, $extraData) {
            $invoiceNumber = $this->documentNumber->generate('INV', 'invoices');

            $subtotal = $order->items->sum(fn($item) => $item->unit * $item->price);
            $ppnRate = 1.1;
            $ppnAmount = $subtotal * ($ppnRate / 100);
            $total = $subtotal + $ppnAmount;

            $invoice = Invoice::create([
                'invoice_number' => $invoiceNumber,
                'order_id' => $order->id,
                'customer_id' => $order->customer_id,
                'customer_po_number' => $order->customer_po_number,
                'customer_spb_number' => $order->customer_spb_number,
                'invoice_date' => now(),
                'due_date' => now()->addDays(30),
                'subtotal' => $subtotal,
                'ppn_rate' => $ppnRate,
                'ppn_amount' => $ppnAmount,
                'invoice_total' => $total,
                'paid_amount' => 0,
                'status' => InvoiceStatus::DRAFT,
                'notes' => $extraData['notes'] ?? null,
            ]);

            $this->activityLog->log(
                module: 'invoice',
                recordId: $invoice->id,
                action: 'created',
                description: "Invoice {$invoice->invoice_number} dibuat untuk Order {$order->order_number}",
            );

            return $invoice;
        });
    }

    public function updateStatusFromPayments(Invoice $invoice): Invoice
    {
        $totalPaid = $invoice->payments()->sum('amount');
        $invoice->update(['paid_amount' => $totalPaid]);

        if ($totalPaid <= 0) {
            $status = $invoice->due_date->isPast() ? InvoiceStatus::OVERDUE : InvoiceStatus::SENT;
        } elseif ($totalPaid >= $invoice->invoice_total) {
            $status = InvoiceStatus::PAID;
        } else {
            $status = InvoiceStatus::PARTIALLY_PAID;
        }

        if ($invoice->status !== $status) {
            $oldStatus = $invoice->status;
            $invoice->update(['status' => $status]);

            $this->activityLog->log(
                module: 'invoice',
                recordId: $invoice->id,
                action: 'status_changed',
                description: "Status invoice {$invoice->invoice_number} berubah dari {$oldStatus->label()} ke {$status->label()}",
                oldValue: ['status' => $oldStatus->value],
                newValue: ['status' => $status->value],
            );
        }

        return $invoice->fresh();
    }

    public function markAsSent(Invoice $invoice): Invoice
    {
        $invoice->update(['status' => InvoiceStatus::SENT]);

        $this->activityLog->log(
            module: 'invoice',
            recordId: $invoice->id,
            action: 'sent',
            description: "Invoice {$invoice->invoice_number} dikirim ke customer",
        );

        return $invoice->fresh();
    }
}
