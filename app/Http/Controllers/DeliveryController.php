<?php

namespace App\Http\Controllers;

use App\Enums\DeliveryStatus;
use App\Enums\OrderStatus;
use App\Http\Requests\StoreDeliveryRequest;
use App\Http\Requests\UpdateDeliveryRequest;
use App\Models\Delivery;
use App\Models\Order;
use App\Services\ActivityLogService;
use App\Services\DocumentNumberService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DeliveryController extends Controller
{
    public function __construct(
        private DocumentNumberService $documentNumber,
        private ActivityLogService $activityLog,
    ) {}

    public function index(Request $request): View
    {
        $search = $request->query('search');
        $status = $request->query('status');
        $dateFrom = $request->query('date_from');
        $dateTo = $request->query('date_to');

        $deliveries = Delivery::with('order.customer')
            ->when($search, fn($q) => $q->where('delivery_number', 'like', "%{$search}%")
                ->orWhere('driver_name', 'like', "%{$search}%")
                ->orWhere('vehicle_plate_manual', 'like', "%{$search}%"))
            ->when($status, fn($q) => $q->where('status', $status))
            ->when($dateFrom, fn($q) => $q->whereDate('delivery_date', '>=', $dateFrom))
            ->when($dateTo, fn($q) => $q->whereDate('delivery_date', '<=', $dateTo))
            ->latest()
            ->paginate(15);

        $statuses = DeliveryStatus::cases();
        $orders = Order::with('customer')->whereIn('status', [
            OrderStatus::ORDER_RECEIVED,
            OrderStatus::SCHEDULED,
            OrderStatus::IN_TRANSIT,
        ])->latest()->get();

        return view('deliveries.index', compact('deliveries', 'search', 'status', 'dateFrom', 'dateTo', 'statuses', 'orders'));
    }

    public function create(): View
    {
        $orders = Order::with('customer')->whereIn('status', [
            OrderStatus::ORDER_RECEIVED,
            OrderStatus::SCHEDULED,
        ])->latest()->get();

        return view('deliveries.create', compact('orders'));
    }

    public function store(StoreDeliveryRequest $request): RedirectResponse
    {
        try {
            $delivery = DB::transaction(function () use ($request) {
                $deliveryNumber = $this->documentNumber->generate('DEL', 'deliveries');

                $delivery = Delivery::create([
                    'delivery_number' => $deliveryNumber,
                    'order_id' => $request->order_id,
                    'delivery_date' => $request->delivery_date,
                    'driver_name' => $request->driver_name,
                    'vehicle_id' => $request->vehicle_id,
                    'vehicle_plate_manual' => $request->vehicle_plate_manual,
                    'vehicle_type_manual' => $request->vehicle_type_manual,
                    'uang_jalan' => $request->uang_jalan ?? 0,
                    'status' => DeliveryStatus::SCHEDULED,
                    'notes' => $request->notes,
                ]);

                $this->activityLog->log(
                    module: 'delivery',
                    recordId: $delivery->id,
                    action: 'created',
                    description: "Pengiriman {$deliveryNumber} dibuat untuk Order #{$delivery->order->order_number}",
                );

                return $delivery;
            });

            return redirect()->route('deliveries.index')
                ->with('success', 'Pengiriman berhasil dibuat.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal membuat pengiriman: ' . $e->getMessage());
        }
    }

    public function show(Delivery $delivery): View
    {
        $delivery->load('order.customer');
        return view('deliveries.show', compact('delivery'));
    }

    public function edit(Delivery $delivery): View
    {
        $orders = Order::with('customer')->latest()->get();
        return view('deliveries.edit', compact('delivery', 'orders'));
    }

    public function update(UpdateDeliveryRequest $request, Delivery $delivery): RedirectResponse
    {
        try {
            $oldData = $delivery->toArray();
            $delivery->update($request->validated());

            $this->activityLog->log(
                module: 'delivery',
                recordId: $delivery->id,
                action: 'updated',
                description: "Pengiriman {$delivery->delivery_number} diperbarui",
                oldValue: $oldData,
                newValue: $delivery->fresh()->toArray(),
            );

            return redirect()->route('deliveries.index')
                ->with('success', 'Pengiriman berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal memperbarui pengiriman: ' . $e->getMessage());
        }
    }
}
