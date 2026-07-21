<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreInvoiceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'order_id' => 'required|exists:orders,id',
            'invoice_date' => 'required|date',
            'due_date' => 'required|date|after_or_equal:invoice_date',
            'notes' => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'order_id.required' => 'Pilih order terlebih dahulu.',
            'order_id.exists' => 'Order tidak ditemukan.',
            'invoice_date.required' => 'Tanggal invoice harus diisi.',
            'due_date.required' => 'Tanggal jatuh tempo harus diisi.',
            'due_date.after_or_equal' => 'Tanggal jatuh tempo harus setelah atau sama dengan tanggal invoice.',
        ];
    }
}
