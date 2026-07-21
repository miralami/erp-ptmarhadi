<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            CompanySettingSeeder::class,
            UserSeeder::class,
            CustomerSeeder::class,
            OrderSeeder::class,
            DeliverySeeder::class,
            InvoiceSeeder::class,
            PaymentSeeder::class,
        ]);
    }
}
