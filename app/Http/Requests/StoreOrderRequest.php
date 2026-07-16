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
            'date' => 'required|date',
            'product_name' => 'required|string|max:255',
            'quantity' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'customer_id.required' => 'Pilih customer terlebih dahulu.',
            'customer_id.exists' => 'Customer tidak ditemukan.',
            'date.required' => 'Tanggal order harus diisi.',
            'date.date' => 'Format tanggal tidak valid.',
            'product_name.required' => 'Nama barang harus diisi.',
            'product_name.max' => 'Nama barang maksimal 255 karakter.',
            'quantity.required' => 'Jumlah harus diisi.',
            'quantity.integer' => 'Jumlah harus berupa angka.',
            'quantity.min' => 'Jumlah minimal 1.',
            'price.required' => 'Harga harus diisi.',
            'price.numeric' => 'Harga harus berupa angka.',
            'price.min' => 'Harga tidak boleh negatif.',
        ];
    }
}
