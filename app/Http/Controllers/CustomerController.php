<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCustomerRequest;
use App\Http\Requests\UpdateCustomerRequest;
use App\Models\Customer;
use App\Services\ActivityLogService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CustomerController extends Controller
{
    public function __construct(
        private ActivityLogService $activityLog,
    ) {}

    public function index(Request $request): View
    {
        $search = $request->query('search');

        $customers = Customer::query()
            ->when($search, fn($q) => $q->where('company_name', 'like', "%{$search}%")
                ->orWhere('contact_person', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%"))
            ->latest()
            ->paginate(15);

        return view('customers.index', compact('customers', 'search'));
    }

    public function create(): View
    {
        return view('customers.create');
    }

    public function store(StoreCustomerRequest $request): RedirectResponse
    {
        $customer = Customer::create($request->validated());

        $this->activityLog->log(
            module: 'customer',
            recordId: $customer->id,
            action: 'created',
            description: "Customer {$customer->company_name} ditambahkan",
        );

        return redirect()->route('customers.index')
            ->with('success', 'Customer berhasil ditambahkan.');
    }

    public function show(Customer $customer): View
    {
        $customer->load(['orders' => fn($q) => $q->with('items')->latest()->limit(10)]);
        $customer->load(['invoices' => fn($q) => $q->with('payments')->latest()->limit(10)]);

        return view('customers.show', compact('customer'));
    }

    public function edit(Customer $customer): View
    {
        return view('customers.edit', compact('customer'));
    }

    public function update(UpdateCustomerRequest $request, Customer $customer): RedirectResponse
    {
        $oldData = $customer->toArray();
        $customer->update($request->validated());

        $this->activityLog->log(
            module: 'customer',
            recordId: $customer->id,
            action: 'updated',
            description: "Customer {$customer->company_name} diperbarui",
            oldValue: $oldData,
            newValue: $customer->toArray(),
        );

        return redirect()->route('customers.index')
            ->with('success', 'Customer berhasil diperbarui.');
    }

    public function destroy(Customer $customer): RedirectResponse
    {
        $name = $customer->company_name;
        $customer->delete();

        $this->activityLog->log(
            module: 'customer',
            recordId: $customer->id,
            action: 'deleted',
            description: "Customer {$name} dihapus",
        );

        return redirect()->route('customers.index')
            ->with('success', 'Customer berhasil dihapus.');
    }
}
