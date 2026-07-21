<?php

namespace App\Http\Controllers;

use App\Enums\PaymentMethod;
use App\Http\Requests\StorePaymentRequest;
use App\Http\Requests\UpdatePaymentRequest;
use App\Models\Invoice;
use App\Models\Payment;
use App\Services\ActivityLogService;
use App\Services\PaymentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PaymentController extends Controller
{
    public function __construct(
        private PaymentService $paymentService,
        private ActivityLogService $activityLog,
    ) {}

    public function index(Request $request): View
    {
        $search = $request->query('search');
        $method = $request->query('method');
        $dateFrom = $request->query('date_from');
        $dateTo = $request->query('date_to');

        $payments = Payment::with('invoice.order.customer')
            ->when($search, fn($q) => $q->where('payment_number', 'like', "%{$search}%")
                ->orWhere('reference_number', 'like', "%{$search}%"))
            ->when($method, fn($q) => $q->where('payment_method', $method))
            ->when($dateFrom, fn($q) => $q->whereDate('payment_date', '>=', $dateFrom))
            ->when($dateTo, fn($q) => $q->whereDate('payment_date', '<=', $dateTo))
            ->latest()
            ->paginate(15);

        $methods = PaymentMethod::cases();

        return view('payments.index', compact('payments', 'search', 'method', 'dateFrom', 'dateTo', 'methods'));
    }

    public function create(): View
    {
        $invoices = Invoice::with('customer')
            ->whereIn('status', [
                \App\Enums\InvoiceStatus::SENT,
                \App\Enums\InvoiceStatus::OVERDUE,
                \App\Enums\InvoiceStatus::PARTIALLY_PAID,
            ])
            ->latest()
            ->get();

        $methods = PaymentMethod::cases();

        return view('payments.create', compact('invoices', 'methods'));
    }

    public function store(StorePaymentRequest $request): RedirectResponse
    {
        try {
            $invoice = Invoice::findOrFail($request->invoice_id);
            $payment = $this->paymentService->recordPayment($invoice, $request->validated());

            return redirect()->route('payments.index')
                ->with('success', "Pembayaran {$payment->payment_number} berhasil dicatat.");
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal mencatat pembayaran: ' . $e->getMessage());
        }
    }

    public function show(Payment $payment): View
    {
        $payment->load('invoice.order.customer');
        return view('payments.show', compact('payment'));
    }

    public function edit(Payment $payment): View
    {
        $invoices = Invoice::with('customer')->latest()->get();
        $methods = PaymentMethod::cases();
        return view('payments.edit', compact('payment', 'invoices', 'methods'));
    }

    public function update(UpdatePaymentRequest $request, Payment $payment): RedirectResponse
    {
        try {
            $oldData = $payment->toArray();
            $payment->update($request->validated());

            $invoice = $payment->invoice;
            app(\App\Services\InvoiceService::class)->updateStatusFromPayments($invoice);

            $this->activityLog->log(
                module: 'payment',
                recordId: $payment->id,
                action: 'updated',
                description: "Pembayaran {$payment->payment_number} diperbarui",
                oldValue: $oldData,
                newValue: $payment->fresh()->toArray(),
            );

            return redirect()->route('payments.index')
                ->with('success', 'Pembayaran berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal memperbarui pembayaran: ' . $e->getMessage());
        }
    }
}
