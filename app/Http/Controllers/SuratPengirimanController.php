<?php

namespace App\Http\Controllers;

use App\Enums\OrderCategory;
use App\Enums\OrderStatus;
use App\Enums\VehicleSource;
use App\Models\CompanySetting;
use App\Models\Customer;
use App\Models\Delivery;
use App\Models\DeliveryExpense;
use App\Models\Order;
use App\Services\ActivityLogService;
use App\Services\CompanySettingService;
use App\Services\DocumentNumberService;
use App\Services\OrderStatusService;
use App\Services\VehicleService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class SuratPengirimanController extends Controller
{
    public function __construct(
        private DocumentNumberService $documentNumber,
        private OrderStatusService $orderStatus,
        private ActivityLogService $activityLog,
        private VehicleService $vehicleService,
        private CompanySettingService $companySettings,
    ) {}

    public function index(Request $request): View
    {
        $search = $request->query('search');
        $status = $request->query('status');
        $customerId = $request->query('customer_id');
        $category = $request->query('category');
        $dateFrom = $request->query('date_from');
        $dateTo = $request->query('date_to');

        $orders = Order::with('customer', 'delivery')
            ->when($search, fn($q) => $q->where('order_number', 'like', "%{$search}%")
                ->orWhereHas('customer', fn($q) => $q->where('company_name', 'like', "%{$search}%"))
                ->orWhere('origin_city', 'like', "%{$search}%")
                ->orWhere('destination_city', 'like', "%{$search}%"))
            ->when($status, fn($q) => $q->where('status', $status))
            ->when($customerId, fn($q) => $q->where('customer_id', $customerId))
            ->when($category, fn($q) => $q->where('category', $category))
            ->when($dateFrom, fn($q) => $q->whereDate('order_date', '>=', $dateFrom))
            ->when($dateTo, fn($q) => $q->whereDate('order_date', '<=', $dateTo))
            ->latest()
            ->paginate(15);

        $pendingOrders = Order::with('customer', 'delivery')
            ->where('status', OrderStatus::ORDER_RECEIVED)
            ->whereDoesntHave('delivery', fn($q) => $q->whereNotNull('driver_name'))
            ->latest()
            ->get();

        $statuses = OrderStatus::cases();
        $categories = OrderCategory::cases();

        return view('surat-pengiriman.index', compact('orders', 'pendingOrders', 'search', 'status', 'customerId', 'category', 'dateFrom', 'dateTo', 'statuses', 'categories'));
    }

    public function create(): View
    {
        $statuses = OrderStatus::cases();
        $categories = OrderCategory::cases();
        $vehicleSources = VehicleSource::cases();
        $vehicles = $this->vehicleService->getActive();
        $companyName = CompanySetting::where('key', 'company_name')->value('value') ?? 'PT Marhadi';

        return view('surat-pengiriman.create', compact('statuses', 'categories', 'vehicleSources', 'vehicles', 'companyName'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'received_by' => 'nullable|string|max:255',
            'order_date' => 'required|date',
            'origin_company' => 'nullable|string|max:255',
            'origin_city' => 'nullable|string|max:255',
            'destination_city' => 'nullable|string|max:255',
            'category' => 'nullable|string|in:' . implode(',', array_map(fn($c) => $c->value, OrderCategory::cases())),
            'vehicle_source' => 'nullable|string|in:' . implode(',', array_map(fn($c) => $c->value, VehicleSource::cases())),
            'customer_po_number' => 'nullable|string|max:255',
            'customer_spb_number' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.product_name' => 'required|string|max:255',
            'items.*.unit' => 'nullable|integer|min:0',
            'items.*.kubikasi' => 'nullable|numeric|min:0',
            'items.*.price' => 'required|numeric|min:0',
            'items.*.max_slot' => 'nullable|integer|min:0',
            'items.*.police_fee' => 'nullable|numeric|min:0',
        ]);

        try {
            $order = DB::transaction(function () use ($validated) {
                $orderNumber = $this->documentNumber->generate('SP', 'orders');

                $order = Order::create([
                    'customer_id' => $validated['customer_id'],
                    'order_number' => $orderNumber,
                    'order_date' => $validated['order_date'],
                    'received_by' => $validated['received_by'] ?? null,
                    'origin_company' => $validated['origin_company'] ?? null,
                    'origin_city' => $validated['origin_city'] ?? null,
                    'destination_city' => $validated['destination_city'] ?? null,
                    'category' => $validated['category'] ?? null,
                    'vehicle_source' => $validated['vehicle_source'] ?? null,
                    'customer_po_number' => $validated['customer_po_number'] ?? null,
                    'customer_spb_number' => $validated['customer_spb_number'] ?? null,
                    'status' => OrderStatus::ORDER_RECEIVED,
                    'notes' => $validated['notes'] ?? null,
                ]);

                foreach ($validated['items'] as $item) {
                    $order->items()->create([
                        'product_name' => $item['product_name'],
                        'unit' => $item['unit'] ?? 0,
                        'kubikasi' => $item['kubikasi'] ?? null,
                        'price' => $item['price'],
                        'max_slot' => $item['max_slot'] ?? null,
                        'police_fee' => $item['police_fee'] ?? 0,
                        'threshold_exceeded' => ($item['police_fee'] ?? 0) > 0,
                    ]);
                }

                $this->activityLog->log(
                    module: 'order',
                    recordId: $order->id,
                    action: 'created',
                    description: "SP {$orderNumber} dibuat untuk {$order->customer->company_name}",
                );

                return $order;
            });

            return redirect()->route('surat-pengiriman.show', $order)
                ->with('success', "Surat Pengiriman {$order->order_number} berhasil dibuat.");
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal membuat SP: ' . $e->getMessage());
        }
    }

    public function show(Order $surat_pengiriman): View
    {
        $order = $surat_pengiriman->load(['customer', 'items', 'delivery.vehicle', 'delivery.expenses', 'invoice']);
        $vehicles = $this->vehicleService->getActive();

        return view('surat-pengiriman.show', compact('order', 'vehicles'));
    }

    public function updateStatus(Request $request, Order $surat_pengiriman): RedirectResponse
    {
        $validated = $request->validate([
            'status' => 'required|string|in:' . implode(',', array_map(fn($c) => $c->value, OrderStatus::cases())),
        ]);

        try {
            $newStatus = OrderStatus::from($validated['status']);

            $delivery = $surat_pengiriman->delivery;

            if ($newStatus === OrderStatus::PERJALANAN_BONGKAR) {
                if (!$delivery || empty($delivery->photo_muat)) {
                    return redirect()->back()
                        ->with('error', 'Foto muat harus diupload sebelum melanjutkan ke Perjalanan Bongkar.');
                }
            }

            if ($newStatus === OrderStatus::COMPLETED) {
                if (!$delivery || empty($delivery->photo_bongkar)) {
                    return redirect()->back()
                        ->with('error', 'Foto bongkar harus diupload sebelum menyelesaikan SP.');
                }
            }

            $this->orderStatus->transition($surat_pengiriman, $newStatus);

            return redirect()->back()
                ->with('success', "Status SP {$surat_pengiriman->order_number} berhasil diubah.");
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', $e->getMessage());
        }
    }

    public function updateDelivery(Request $request, Order $surat_pengiriman): RedirectResponse
    {
        $validated = $request->validate([
            'driver_name' => 'nullable|string|max:255',
            'vehicle_id' => 'nullable|exists:vehicles,id',
            'vehicle_plate_manual' => 'nullable|string|max:255',
            'vehicle_type_manual' => 'nullable|string|max:255',
            'uang_jalan' => 'nullable|numeric|min:0',
            'expenses' => 'nullable|array',
            'expenses.*.description' => 'required|string|max:255',
            'expenses.*.amount' => 'required|numeric|min:0',
        ]);

        try {
            DB::transaction(function () use ($surat_pengiriman, $validated) {
                $delivery = $surat_pengiriman->delivery;
                if (!$delivery) {
                    $deliveryNumber = $this->documentNumber->generate('DEL', 'deliveries');
                    $delivery = Delivery::create([
                        'delivery_number' => $deliveryNumber,
                        'order_id' => $surat_pengiriman->id,
                        'delivery_date' => now(),
                        'status' => \App\Enums\DeliveryStatus::SCHEDULED,
                    ]);
                }

                $delivery->update([
                    'driver_name' => $validated['driver_name'] ?? $delivery->driver_name,
                    'vehicle_id' => $validated['vehicle_id'] ?? $delivery->vehicle_id,
                    'vehicle_plate_manual' => $validated['vehicle_plate_manual'] ?? $delivery->vehicle_plate_manual,
                    'vehicle_type_manual' => $validated['vehicle_type_manual'] ?? $delivery->vehicle_type_manual,
                    'uang_jalan' => $validated['uang_jalan'] ?? $delivery->uang_jalan ?? 0,
                ]);

                if (isset($validated['expenses'])) {
                    $delivery->expenses()->delete();
                    foreach ($validated['expenses'] as $expense) {
                        $delivery->expenses()->create($expense);
                    }
                }
            });

            return redirect()->back()
                ->with('success', 'Detail pengiriman berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal memperbarui detail pengiriman: ' . $e->getMessage());
        }
    }

    public function uploadPhotos(Request $request, Order $surat_pengiriman): RedirectResponse
    {
        $request->validate([
            'photo_type' => 'required|string|in:photo_muat,photo_bongkar,photo_surat_jalan',
        ]);

        $delivery = $surat_pengiriman->delivery;
        if (!$delivery) {
            return redirect()->back()->with('error', 'Data pengiriman belum ada.');
        }

        $files = $request->file('photos');
        if (!$files) {
            return redirect()->back()->with('error', 'Pilih foto terlebih dahulu.');
        }

        $files = is_array($files) ? $files : [$files];

        $paths = [];
        foreach ($files as $photo) {
            if ($photo->isValid()) {
                $path = $photo->store("sp-photos/{$surat_pengiriman->id}", 'public');
                $paths[] = $path;
            }
        }

        if (empty($paths)) {
            return redirect()->back()->with('error', 'Gagal mengupload foto. Coba file dengan format lain.');
        }

        $existing = $delivery->{$request->photo_type} ?? [];
        $delivery->update([$request->photo_type => array_merge($existing, $paths)]);

        return redirect()->back()->with('success', count($paths) . ' foto berhasil diupload.');
    }

    public function edit(Order $surat_pengiriman): View
    {
        $order = $surat_pengiriman->load(['customer', 'items']);
        $statuses = OrderStatus::cases();
        $categories = OrderCategory::cases();
        $vehicleSources = VehicleSource::cases();
        $vehicles = $this->vehicleService->getActive();

        return view('surat-pengiriman.edit', compact('order', 'statuses', 'categories', 'vehicleSources', 'vehicles'));
    }

    public function update(Request $request, Order $surat_pengiriman): RedirectResponse
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'received_by' => 'nullable|string|max:255',
            'order_date' => 'required|date',
            'origin_company' => 'nullable|string|max:255',
            'origin_city' => 'nullable|string|max:255',
            'destination_city' => 'nullable|string|max:255',
            'category' => 'nullable|string|in:' . implode(',', array_map(fn($c) => $c->value, OrderCategory::cases())),
            'vehicle_source' => 'nullable|string|in:' . implode(',', array_map(fn($c) => $c->value, VehicleSource::cases())),
            'customer_po_number' => 'nullable|string|max:255',
            'customer_spb_number' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.product_name' => 'required|string|max:255',
            'items.*.unit' => 'nullable|integer|min:0',
            'items.*.kubikasi' => 'nullable|numeric|min:0',
            'items.*.price' => 'required|numeric|min:0',
            'items.*.max_slot' => 'nullable|integer|min:0',
            'items.*.police_fee' => 'nullable|numeric|min:0',
        ]);

        try {
            DB::transaction(function () use ($surat_pengiriman, $validated) {
                $old = $surat_pengiriman->toArray();

                $surat_pengiriman->update([
                    'customer_id' => $validated['customer_id'],
                    'order_date' => $validated['order_date'],
                    'received_by' => $validated['received_by'] ?? null,
                    'origin_company' => $validated['origin_company'] ?? null,
                    'origin_city' => $validated['origin_city'] ?? null,
                    'destination_city' => $validated['destination_city'] ?? null,
                    'category' => $validated['category'] ?? null,
                    'vehicle_source' => $validated['vehicle_source'] ?? null,
                    'customer_po_number' => $validated['customer_po_number'] ?? null,
                    'customer_spb_number' => $validated['customer_spb_number'] ?? null,
                    'notes' => $validated['notes'] ?? null,
                ]);

                $surat_pengiriman->items()->delete();
                foreach ($validated['items'] as $item) {
                    $surat_pengiriman->items()->create([
                        'product_name' => $item['product_name'],
                        'unit' => $item['unit'] ?? 0,
                        'kubikasi' => $item['kubikasi'] ?? null,
                        'price' => $item['price'],
                        'max_slot' => $item['max_slot'] ?? null,
                        'police_fee' => $item['police_fee'] ?? 0,
                        'threshold_exceeded' => ($item['police_fee'] ?? 0) > 0,
                    ]);
                }

                $this->activityLog->log(
                    module: 'order',
                    recordId: $surat_pengiriman->id,
                    action: 'updated',
                    description: "SP {$surat_pengiriman->order_number} diperbarui",
                    oldValue: $old,
                    newValue: $surat_pengiriman->fresh()->toArray(),
                );
            });

            return redirect()->route('surat-pengiriman.show', $surat_pengiriman)
                ->with('success', "SP {$surat_pengiriman->order_number} berhasil diperbarui.");
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal memperbarui SP: ' . $e->getMessage());
        }
    }

    public function cetak(Order $surat_pengiriman): View
    {
        $order = $surat_pengiriman->load(['customer', 'items', 'delivery']);

        $settings = $this->companySettings->getAll();

        return view('surat-pengiriman.cetak', compact('order', 'settings'))
            ->with('companyName', $settings['company_name'] ?? 'PT Marhadi')
            ->with('companyAddress', $settings['address'] ?? '')
            ->with('companyPhone', $settings['phone'] ?? '')
            ->with('companyEmail', $settings['email'] ?? '')
            ->with('signatureName', $settings['signature_name'] ?? '');
    }
}
