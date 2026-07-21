<?php

namespace App\Http\Requests;

use App\Enums\InvoiceStatus;
use Illuminate\Foundation\Http\FormRequest;

class UpdateInvoiceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'invoice_date' => 'required|date',
            'due_date' => 'required|date|after_or_equal:invoice_date',
            'status' => 'required|string|in:' . implode(',', array_map(fn($c) => $c->value, InvoiceStatus::cases())),
            'notes' => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'invoice_date.required' => 'Tanggal invoice harus diisi.',
            'due_date.required' => 'Tanggal jatuh tempo harus diisi.',
            'due_date.after_or_equal' => 'Tanggal jatuh tempo harus setelah atau sama dengan tanggal invoice.',
            'status.in' => 'Status invoice tidak valid.',
        ];
    }
}
