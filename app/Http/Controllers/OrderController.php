<?php

namespace App\Http\Controllers;

use App\Enums\OrderStatus;
use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateOrderRequest;
use App\Models\Customer;
use App\Models\Order;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->query('search');

        $orders = Order::with('customer')
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('order_number', 'like', "%{$search}%")
                        ->orWhereHas('customer', function ($cq) use ($search) {
                            $cq->where('name', 'like', "%{$search}%");
                        });
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

    public function store(StoreOrderRequest $request): RedirectResponse
    {
        try {
            $order = DB::transaction(function () use ($request) {
                $data = $request->validated();
                $data['order_number'] = Order::generateOrderNumber();
                $data['status'] = OrderStatus::ORDER_RECEIVED;
                return Order::create($data);
            });

            return redirect()->route('orders.index')
                ->with('success', 'Order berhasil dibuat.');
        } catch (QueryException $e) {
            Log::error('Order creation failed', [
                'error' => $e->getMessage(),
                'data' => $request->validated(),
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal membuat order. Silakan coba lagi.');
        }
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

    public function update(UpdateOrderRequest $request, Order $order): RedirectResponse
    {
        try {
            DB::transaction(function () use ($request, $order) {
                $order->update($request->validated());
            });

            return redirect()->route('orders.index')
                ->with('success', 'Order berhasil diperbarui.');
        } catch (QueryException $e) {
            Log::error('Order update failed', [
                'error' => $e->getMessage(),
                'order_id' => $order->id,
                'data' => $request->validated(),
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal memperbarui order. Silakan coba lagi.');
        }
    }
}
