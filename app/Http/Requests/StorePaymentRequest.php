<?php

namespace App\Http\Requests;

use App\Enums\PaymentMethod;
use Illuminate\Foundation\Http\FormRequest;

class StorePaymentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'invoice_id' => 'required|exists:invoices,id',
            'payment_date' => 'required|date',
            'amount' => 'required|numeric|min:0',
            'payment_method' => 'required|string|in:' . implode(',', array_map(fn($c) => $c->value, PaymentMethod::cases())),
            'reference_number' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'invoice_id.required' => 'Pilih invoice terlebih dahulu.',
            'payment_date.required' => 'Tanggal pembayaran harus diisi.',
            'amount.required' => 'Jumlah pembayaran harus diisi.',
            'amount.min' => 'Jumlah pembayaran tidak boleh negatif.',
            'payment_method.required' => 'Metode pembayaran harus dipilih.',
            'payment_method.in' => 'Metode pembayaran tidak valid.',
        ];
    }
}
