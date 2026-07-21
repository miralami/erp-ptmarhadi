<?php

namespace App\Http\Controllers;

use App\Enums\VehicleType;
use App\Models\Vehicle;
use App\Services\ActivityLogService;
use App\Services\VehicleService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class VehicleController extends Controller
{
    public function __construct(
        private VehicleService $vehicleService,
        private ActivityLogService $activityLog,
    ) {}

    public function index(Request $request): View
    {
        $search = $request->query('search');
        $status = $request->query('status');
        $type = $request->query('type');

        $vehicles = $this->vehicleService->getAll(compact('search', 'status', 'type'));

        $types = VehicleType::cases();

        return view('kendaraan.index', compact('vehicles', 'search', 'status', 'type', 'types'));
    }

    public function create(): View
    {
        $types = VehicleType::cases();

        return view('kendaraan.create', compact('types'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'brand' => 'required|string|max:255',
            'model' => 'required|string|max:255',
            'plate_number' => 'required|string|max:20|unique:vehicles,plate_number',
            'type' => 'required|string|in:' . implode(',', array_map(fn($c) => $c->value, VehicleType::cases())),
            'status' => 'required|string|in:ACTIVE,INACTIVE',
            'notes' => 'nullable|string',
        ]);

        $this->vehicleService->create($validated);

        return redirect()->route('kendaraan.index')
            ->with('success', 'Kendaraan berhasil ditambahkan.');
    }

    public function edit(Vehicle $kendaraan): View
    {
        $types = VehicleType::cases();

        return view('kendaraan.edit', compact('kendaraan', 'types'));
    }

    public function update(Request $request, Vehicle $kendaraan): RedirectResponse
    {
        $validated = $request->validate([
            'brand' => 'required|string|max:255',
            'model' => 'required|string|max:255',
            'plate_number' => 'required|string|max:20|unique:vehicles,plate_number,' . $kendaraan->id,
            'type' => 'required|string|in:' . implode(',', array_map(fn($c) => $c->value, VehicleType::cases())),
            'status' => 'required|string|in:ACTIVE,INACTIVE',
            'notes' => 'nullable|string',
        ]);

        $this->vehicleService->update($kendaraan, $validated);

        return redirect()->route('kendaraan.index')
            ->with('success', 'Kendaraan berhasil diperbarui.');
    }

    public function destroy(Vehicle $kendaraan): RedirectResponse
    {
        $this->vehicleService->deactivate($kendaraan);

        return redirect()->route('kendaraan.index')
            ->with('success', 'Kendaraan berhasil dinonaktifkan.');
    }
}
