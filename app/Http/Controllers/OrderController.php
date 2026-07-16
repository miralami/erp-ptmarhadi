<?php

namespace App\Http\Controllers;

use App\Enums\OrderStatus;
use App\Models\Customer;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->query('search');

        $orders = Order::with('customer')
            ->when($search, function ($query, $search) {
                $query->where('order_number', 'like', "%{$search}%")
                    ->orWhereHas('customer', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%");
                    });
            })
            ->latest()
            ->paginate(15);

        return view('orders.index', compact('orders', 'search'));
    }

    public function create(): View
    {
        $customers = Customer::pluck('name', 'id');
        return view('orders.create', compact('customers'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'date' => 'required|date',
            'product_name' => 'required|string|max:255',
            'quantity' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        $lastOrder = Order::latest('id')->first();
        $nextId = $lastOrder ? $lastOrder->id + 1 : 1;
        $data['order_number'] = 'ORD-' . now()->format('ymd') . '-' . str_pad((string)$nextId, 4, '0', STR_PAD_LEFT);
        $data['status'] = OrderStatus::ORDER_RECEIVED;

        Order::create($data);

        return redirect()->route('orders.index')->with('success', 'Order berhasil dibuat.');
    }

    public function show(Order $order): View
    {
        $order->load('customer');
        return view('orders.show', compact('order'));
    }

    public function edit(Order $order): View
    {
        $customers = Customer::pluck('name', 'id');
        return view('orders.edit', compact('order', 'customers'));
    }

    public function update(Request $request, Order $order): RedirectResponse
    {
        $data = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'date' => 'required|date',
            'product_name' => 'required|string|max:255',
            'quantity' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
            'status' => 'required|string|in:' . implode(',', array_column(OrderStatus::cases(), 'value')),
        ]);

        $order->update($data);

        return redirect()->route('orders.index')->with('success', 'Order berhasil diperbarui.');
    }
}
