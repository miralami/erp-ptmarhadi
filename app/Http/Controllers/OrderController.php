<?php

namespace App\Http\Controllers;

use App\Enums\OrderStatus;
use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateOrderRequest;
use App\Models\Customer;
use App\Models\Order;
use App\Services\ActivityLogService;
use App\Services\DocumentNumberService;
use App\Services\OrderStatusService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function __construct(
        private DocumentNumberService $documentNumber,
        private OrderStatusService $orderStatusService,
        private ActivityLogService $activityLog,
    ) {}

    public function index(Request $request): View
    {
        $search = $request->query('search');
        $status = $request->query('status');
        $dateFrom = $request->query('date_from');
        $dateTo = $request->query('date_to');
        $customerId = $request->query('customer_id');

        $orders = Order::with('customer', 'items')
            ->when($search, fn($q) => $q->where('order_number', 'like', "%{$search}%")
                ->orWhereHas('customer', fn($cq) => $cq->where('company_name', 'like', "%{$search}%")))
            ->when($status, fn($q) => $q->where('status', $status))
            ->when($dateFrom, fn($q) => $q->whereDate('order_date', '>=', $dateFrom))
            ->when($dateTo, fn($q) => $q->whereDate('order_date', '<=', $dateTo))
            ->when($customerId, fn($q) => $q->where('customer_id', $customerId))
            ->latest()
            ->paginate(15);

        $customers = Customer::pluck('company_name', 'id');
        $statuses = OrderStatus::cases();

        return view('orders.index', compact('orders', 'search', 'status', 'dateFrom', 'dateTo', 'customerId', 'customers', 'statuses'));
    }

    public function create(): View
    {
        $customers = Customer::pluck('company_name', 'id');
        return view('orders.create', compact('customers'));
    }

    public function store(StoreOrderRequest $request): RedirectResponse
    {
        try {
            $order = DB::transaction(function () use ($request) {
                $orderNumber = $this->documentNumber->generate('ORD', 'orders');
                $data = $request->validated();

                $order = Order::create([
                    'customer_id' => $data['customer_id'],
                    'order_number' => $orderNumber,
                    'order_date' => $data['order_date'],
                    'status' => OrderStatus::ORDER_RECEIVED,
                    'notes' => $data['notes'] ?? null,
                ]);

                foreach ($data['items'] as $item) {
                    $order->items()->create($item);
                }

                $this->activityLog->log(
                    module: 'order',
                    recordId: $order->id,
                    action: 'created',
                    description: "Order {$orderNumber} dibuat dengan " . count($data['items']) . " item",
                );

                return $order;
            });

            return redirect()->route('orders.index')
                ->with('success', 'Order berhasil dibuat.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal membuat order: ' . $e->getMessage());
        }
    }

    public function show(Order $order): View
    {
        $order->load(['customer', 'items', 'delivery', 'invoice.payments']);
        return view('orders.show', compact('order'));
    }

    public function edit(Order $order): View
    {
        $customers = Customer::pluck('company_name', 'id');
        return view('orders.edit', compact('order', 'customers'));
    }

    public function update(UpdateOrderRequest $request, Order $order): RedirectResponse
    {
        try {
            $oldData = $order->toArray();
            $order->update($request->validated());

            $this->activityLog->log(
                module: 'order',
                recordId: $order->id,
                action: 'updated',
                description: "Order {$order->order_number} diperbarui",
                oldValue: $oldData,
                newValue: $order->fresh()->toArray(),
            );

            return redirect()->route('orders.index')
                ->with('success', 'Order berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal memperbarui order: ' . $e->getMessage());
        }
    }

    public function updateStatus(Request $request, Order $order): RedirectResponse
    {
        $request->validate([
            'status' => 'required|string',
        ]);

        try {
            $newStatus = OrderStatus::from($request->status);
            $oldStatus = $order->status;

            $this->orderStatusService->transition($order, $newStatus);

            $this->activityLog->log(
                module: 'order',
                recordId: $order->id,
                action: 'status_changed',
                description: "Status order {$order->order_number} berubah dari {$oldStatus->label()} ke {$newStatus->label()}",
                oldValue: ['status' => $oldStatus->value],
                newValue: ['status' => $newStatus->value],
            );

            return redirect()->back()->with('success', 'Status order berhasil diperbarui.');
        } catch (\InvalidArgumentException $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
