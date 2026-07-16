<?php

namespace Database\Seeders;

use App\Models\Customer;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    public function run(): void
    {
        $customers = [
            ['name' => 'PT Sinar Abadi', 'address' => 'Jl. Merdeka No. 45, Jakarta', 'email' => 'sinarabadi@gmail.com', 'phone' => '021-1234567'],
            ['name' => 'PT Maju Bersama', 'address' => 'Jl. Sudirman No. 88, Bandung', 'email' => 'majubersama@gmail.com', 'phone' => '022-2345678'],
            ['name' => 'PT Nusantara Jaya', 'address' => 'Jl. Gatot Subroto No. 12, Surabaya', 'email' => 'nusantarajaya@gmail.com', 'phone' => '031-3456789'],
            ['name' => 'PT Cipta Mandiri', 'address' => 'Jl. Diponegoro No. 67, Semarang', 'email' => 'ciptamandiri@gmail.com', 'phone' => '024-4567890'],
            ['name' => 'PT Bumi Sejahtera', 'address' => 'Jl. A. Yani No. 23, Medan', 'email' => 'bumisejahtera@gmail.com', 'phone' => '061-5678901'],
            ['name' => 'PT Karya Utama', 'address' => 'Jl. Pahlawan No. 9, Makassar', 'email' => 'karyautama@gmail.com', 'phone' => '0411-678902'],
            ['name' => 'PT Indah Purnama', 'address' => 'Jl. Imam Bonjol No. 34, Palembang', 'email' => 'indahpurnama@gmail.com', 'phone' => '0711-789012'],
            ['name' => 'PT Harapan Bangsa', 'address' => 'Jl. Jenderal Ahmad Yani No. 56, Yogyakarta', 'email' => 'harapanbangsa@gmail.com', 'phone' => '0274-890123'],
            ['name' => 'PT Sentosa Abadi', 'address' => 'Jl. Raya No. 78, Denpasar', 'email' => 'sentosaabadi@gmail.com', 'phone' => '0361-901234'],
            ['name' => 'PT Mitra Sukses', 'address' => 'Jl. Dr. Sutomo No. 15, Pontianak', 'email' => 'mitrasukses@gmail.com', 'phone' => '0561-012345'],
            ['name' => 'PT Delta Pratama', 'address' => 'Jl. Veteran No. 29, Balikpapan', 'email' => 'deltapratama@gmail.com', 'phone' => '0542-123456'],
            ['name' => 'PT Kencana Mulia', 'address' => 'Jl. Merapi No. 11, Malang', 'email' => 'kencanamulia@gmail.com', 'phone' => '0341-234567'],
            ['name' => 'PT Bintang Timur', 'address' => 'Jl. Flamboyan No. 7, Manado', 'email' => 'bintangtimur@gmail.com', 'phone' => '0431-345678'],
            ['name' => 'PT Palma Indah', 'address' => 'Jl. Kelapa Sawit No. 3, Pekanbaru', 'email' => 'palmaindah@gmail.com', 'phone' => '0761-456789'],
            ['name' => 'PT Guna Persada', 'address' => 'Jl. Cendrawasih No. 19, Ambon', 'email' => 'gunapersada@gmail.com', 'phone' => '0911-567890'],
        ];

        foreach ($customers as $customer) {
            Customer::create($customer);
        }
    }
}
