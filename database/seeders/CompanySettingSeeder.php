<?php

namespace Database\Seeders;

use App\Models\CompanySetting;
use Illuminate\Database\Seeder;

class CompanySettingSeeder extends Seeder
{
    public function run(): void
    {
        $defaults = [
            'company_name' => 'PT Marhadi',
            'npwp' => '01.234.567.8-999.000',
            'address' => 'Jl. Contoh No. 123, Jakarta',
            'phone' => '021-12345678',
            'email' => 'info@marhadi.com',
            'bank_name' => 'Bank Mandiri',
            'bank_account' => '123-00-1234567-8',
            'bank_branch' => 'Jakarta Pusat',
            'signature_name' => 'Direktur PT Marhadi',
        ];

        foreach ($defaults as $key => $value) {
            CompanySetting::updateOrCreate(
                ['key' => $key],
                ['value' => $value],
            );
        }
    }
}
