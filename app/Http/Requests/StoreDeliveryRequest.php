<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDeliveryRequest extends FormRequest
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
            'notes' => 'nullable|string',
        ];
    }
}
