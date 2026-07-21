<?php

namespace App\Http\Requests;

use App\Enums\DeliveryStatus;
use Illuminate\Foundation\Http\FormRequest;

class UpdateDeliveryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'order_id' => 'required|exists:orders,id',
            'delivery_date' => 'required|date',
            'driver_name' => 'nullable|string|max:255',
            'vehicle_id' => 'nullable|exists:vehicles,id',
            'vehicle_plate_manual' => 'nullable|string|max:255',
            'vehicle_type_manual' => 'nullable|string|max:255',
            'uang_jalan' => 'nullable|numeric|min:0',
            'status' => 'required|string|in:' . implode(',', array_map(fn($c) => $c->value, DeliveryStatus::cases())),
            'notes' => 'nullable|string',
            'photo_muat' => 'nullable|json',
            'photo_bongkar' => 'nullable|json',
            'photo_surat_jalan' => 'nullable|json',
        ];
    }
}
