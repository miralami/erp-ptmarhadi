<?php

namespace App\Services;

use App\Models\Vehicle;

class VehicleService
{
    public function __construct(
        private ActivityLogService $activityLog,
    ) {}

    public function getAll(array $filters = [])
    {
        $query = Vehicle::query();

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['type'])) {
            $query->where('type', $filters['type']);
        }

        if (!empty($filters['search'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('plate_number', 'like', '%' . $filters['search'] . '%')
                  ->orWhere('brand', 'like', '%' . $filters['search'] . '%')
                  ->orWhere('model', 'like', '%' . $filters['search'] . '%');
            });
        }

        return $query->latest()->paginate(15);
    }

    public function getActive()
    {
        return Vehicle::where('status', 'ACTIVE')->orderBy('plate_number')->get();
    }

    public function create(array $data): Vehicle
    {
        $vehicle = Vehicle::create($data);

        $this->activityLog->log(
            module: 'vehicle',
            recordId: $vehicle->id,
            action: 'created',
            description: "Kendaraan {$vehicle->plate_number} ditambahkan",
        );

        return $vehicle;
    }

    public function update(Vehicle $vehicle, array $data): Vehicle
    {
        $old = $vehicle->toArray();
        $vehicle->update($data);

        $this->activityLog->log(
            module: 'vehicle',
            recordId: $vehicle->id,
            action: 'updated',
            description: "Kendaraan {$vehicle->plate_number} diubah",
            oldValue: $old,
            newValue: $vehicle->fresh()->toArray(),
        );

        return $vehicle->fresh();
    }

    public function deactivate(Vehicle $vehicle): Vehicle
    {
        $vehicle->update(['status' => 'INACTIVE']);

        $this->activityLog->log(
            module: 'vehicle',
            recordId: $vehicle->id,
            action: 'deactivated',
            description: "Kendaraan {$vehicle->plate_number} dinonaktifkan",
        );

        return $vehicle->fresh();
    }
}
