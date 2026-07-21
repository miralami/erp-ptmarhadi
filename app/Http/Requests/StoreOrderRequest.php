<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRequest extends FormRequest
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
            'items' => 'required|array|min:1',
            'items.*.product_name' => 'required|string|max:255',
            'items.*.unit' => 'nullable|integer|min:0',
            'items.*.price' => 'required|numeric|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'customer_id.required' => 'Pilih customer terlebih dahulu.',
            'customer_id.exists' => 'Customer tidak ditemukan.',
            'order_date.required' => 'Tanggal order harus diisi.',
            'items.required' => 'Minimal satu item barang harus diisi.',
            'items.*.product_name.required' => 'Nama barang harus diisi.',
            'items.*.price.required' => 'Harga barang harus diisi.',
            'items.*.price.required' => 'Harga barang harus diisi.',
            'items.*.price.min' => 'Harga barang tidak boleh negatif.',
        ];
    }
}
