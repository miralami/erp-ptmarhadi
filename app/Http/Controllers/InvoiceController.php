<?php

namespace App\Http\Controllers;

use App\Enums\InvoiceStatus;
use App\Http\Requests\StoreInvoiceRequest;
use App\Http\Requests\UpdateInvoiceRequest;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Order;
use App\Services\ActivityLogService;
use App\Services\CompanySettingService;
use App\Services\InvoiceService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class InvoiceController extends Controller
{
    public function __construct(
        private InvoiceService $invoiceService,
        private ActivityLogService $activityLog,
    ) {}

    public function index(Request $request): View
    {
        $search = $request->query('search');
        $status = $request->query('status');
        $customerId = $request->query('customer_id');
        $overdue = $request->query('overdue');

        $invoices = Invoice::with('customer', 'order')
            ->when($search, fn($q) => $q->where('invoice_number', 'like', "%{$search}%"))
            ->when($status, fn($q) => $q->where('status', $status))
            ->when($customerId, fn($q) => $q->where('customer_id', $customerId))
            ->when($overdue, fn($q) => $q->where('due_date', '<', now())->whereNotIn('status', [InvoiceStatus::PAID, InvoiceStatus::VOID]))
            ->latest()
            ->paginate(15);

        $customers = Customer::pluck('company_name', 'id');
        $statuses = InvoiceStatus::cases();

        return view('invoices.index', compact('invoices', 'search', 'status', 'customerId', 'customers', 'statuses', 'overdue'));
    }

    public function create(): View
    {
        $orders = Order::with('customer', 'items')
            ->whereDoesntHave('invoice')
            ->where('status', \App\Enums\OrderStatus::COMPLETED)
            ->latest()
            ->get();
        $customers = Customer::pluck('company_name', 'id');

        return view('invoices.create', compact('orders', 'customers'));
    }

    public function store(StoreInvoiceRequest $request): RedirectResponse
    {
        try {
            $order = Order::with('items')->findOrFail($request->order_id);

            $invoice = $this->invoiceService->createFromOrder($order, $request->validated());

            return redirect()->route('invoices.index')
                ->with('success', "Invoice {$invoice->invoice_number} berhasil dibuat.");
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal membuat invoice: ' . $e->getMessage());
        }
    }

    public function show(Invoice $invoice): View
    {
        $invoice->load(['customer', 'order.items', 'payments']);
        return view('invoices.show', compact('invoice'));
    }

    public function edit(Invoice $invoice): View
    {
        return view('invoices.edit', compact('invoice'));
    }

    public function update(UpdateInvoiceRequest $request, Invoice $invoice): RedirectResponse
    {
        try {
            $oldData = $invoice->toArray();
            $invoice->update($request->validated());

            $this->activityLog->log(
                module: 'invoice',
                recordId: $invoice->id,
                action: 'updated',
                description: "Invoice {$invoice->invoice_number} diperbarui",
                oldValue: $oldData,
                newValue: $invoice->fresh()->toArray(),
            );

            return redirect()->route('invoices.index')
                ->with('success', 'Invoice berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal memperbarui invoice: ' . $e->getMessage());
        }
    }

    public function send(Invoice $invoice): RedirectResponse
    {
        $this->invoiceService->markAsSent($invoice);

        return redirect()->back()->with('success', "Invoice {$invoice->invoice_number} ditandai sebagai terkirim.");
    }

    public function pdf(Invoice $invoice, CompanySettingService $settings): View
    {
        $invoice->load('customer', 'order.items', 'payments');
        $company = $settings->getAll();
        return view('invoices.pdf', compact('invoice', 'company'));
    }
}
