<?php

namespace App\Http\Requests;

use App\Enums\OrderStatus;
use Illuminate\Foundation\Http\FormRequest;

class UpdateOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'customer_id' => 'required|exists:customers,id',
            'order_date' => 'required|date',
            'notes' => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'customer_id.required' => 'Pilih customer terlebih dahulu.',
            'customer_id.exists' => 'Customer tidak ditemukan.',
            'order_date.required' => 'Tanggal order harus diisi.',
        ];
    }
}
