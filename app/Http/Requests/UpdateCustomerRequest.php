<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCustomerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'company_name' => 'required|string|max:255',
            'contact_person' => 'nullable|string|max:255',
            'npwp' => 'nullable|string|max:20|regex:/^\d{2}\.\d{3}\.\d{3}\.\d{1}-\d{3}\.\d{3}$/',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:255',
            'address' => 'nullable|string',
            'notes' => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'company_name.required' => 'Nama perusahaan harus diisi.',
            'npwp.regex' => 'Format NPWP tidak valid. Gunakan format XX.XXX.XXX.X-XXX.XXX',
        ];
    }
}
