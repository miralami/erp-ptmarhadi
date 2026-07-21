<?php

namespace Database\Seeders;

use App\Models\Customer;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    public function run(): void
    {
        $customers = [
            ['company_name' => 'PT Sinar Abadi', 'contact_person' => 'Budi Santoso', 'address' => 'Jl. Merdeka No. 45, Jakarta', 'email' => 'sinarabadi@gmail.com', 'phone' => '021-1234567'],
            ['company_name' => 'PT Maju Bersama', 'contact_person' => 'Siti Rahmawati', 'address' => 'Jl. Sudirman No. 88, Bandung', 'email' => 'majubersama@gmail.com', 'phone' => '022-2345678'],
            ['company_name' => 'PT Nusantara Jaya', 'contact_person' => 'Ahmad Hidayat', 'address' => 'Jl. Gatot Subroto No. 12, Surabaya', 'email' => 'nusantarajaya@gmail.com', 'phone' => '031-3456789'],
            ['company_name' => 'PT Cipta Mandiri', 'contact_person' => 'Rina Wijaya', 'address' => 'Jl. Diponegoro No. 67, Semarang', 'email' => 'ciptamandiri@gmail.com', 'phone' => '024-4567890'],
            ['company_name' => 'PT Bumi Sejahtera', 'contact_person' => 'Dedi Kusuma', 'address' => 'Jl. A. Yani No. 23, Medan', 'email' => 'bumisejahtera@gmail.com', 'phone' => '061-5678901'],
            ['company_name' => 'PT Karya Utama', 'contact_person' => 'Dewi Lestari', 'address' => 'Jl. Pahlawan No. 9, Makassar', 'email' => 'karyautama@gmail.com', 'phone' => '0411-678902'],
            ['company_name' => 'PT Indah Purnama', 'contact_person' => 'Hendra Gunawan', 'address' => 'Jl. Imam Bonjol No. 34, Palembang', 'email' => 'indahpurnama@gmail.com', 'phone' => '0711-789012'],
            ['company_name' => 'PT Harapan Bangsa', 'contact_person' => 'Indah Permata', 'address' => 'Jl. Jenderal Ahmad Yani No. 56, Yogyakarta', 'email' => 'harapanbangsa@gmail.com', 'phone' => '0274-890123'],
            ['company_name' => 'PT Sentosa Abadi', 'contact_person' => 'Joko Susilo', 'address' => 'Jl. Raya No. 78, Denpasar', 'email' => 'sentosaabadi@gmail.com', 'phone' => '0361-901234'],
            ['company_name' => 'PT Mitra Sukses', 'contact_person' => 'Lina Marlina', 'address' => 'Jl. Dr. Sutomo No. 15, Pontianak', 'email' => 'mitrasukses@gmail.com', 'phone' => '0561-012345'],
            ['company_name' => 'PT Delta Pratama', 'contact_person' => 'Agus Pranoto', 'address' => 'Jl. Veteran No. 29, Balikpapan', 'email' => 'deltapratama@gmail.com', 'phone' => '0542-123456'],
            ['company_name' => 'PT Kencana Mulia', 'contact_person' => 'Sri Wahyuni', 'address' => 'Jl. Merapi No. 11, Malang', 'email' => 'kencanamulia@gmail.com', 'phone' => '0341-234567'],
            ['company_name' => 'PT Bintang Timur', 'contact_person' => 'Eko Prasetyo', 'address' => 'Jl. Flamboyan No. 7, Manado', 'email' => 'bintangtimur@gmail.com', 'phone' => '0431-345678'],
            ['company_name' => 'PT Palma Indah', 'contact_person' => 'Rini Handayani', 'address' => 'Jl. Kelapa Sawit No. 3, Pekanbaru', 'email' => 'palmaindah@gmail.com', 'phone' => '0761-456789'],
            ['company_name' => 'PT Guna Persada', 'contact_person' => 'Adi Saputra', 'address' => 'Jl. Cendrawasih No. 19, Ambon', 'email' => 'gunapersada@gmail.com', 'phone' => '0911-567890'],
            ['company_name' => 'PT Aneka Jaya', 'contact_person' => 'Mega Sari', 'address' => 'Jl. Mangga No. 21, Batam', 'email' => 'anekajaya@gmail.com', 'phone' => '0778-678901'],
            ['company_name' => 'PT Sumber Makmur', 'contact_person' => 'Bayu Nugroho', 'address' => 'Jl. Kenanga No. 55, Cirebon', 'email' => 'sumbermakmur@gmail.com', 'phone' => '0231-789012'],
            ['company_name' => 'PT Cahaya Abadi', 'contact_person' => 'Rizky Pratama', 'address' => 'Jl. Melati No. 33, Tasikmalaya', 'email' => 'cahayaabadi@gmail.com', 'phone' => '0265-890123'],
            ['company_name' => 'PT Duta Kencana', 'contact_person' => 'Fitri Handayani', 'address' => 'Jl. Anggrek No. 17, Samarinda', 'email' => 'dutakencana@gmail.com', 'phone' => '0541-901234'],
            ['company_name' => 'PT Karya Bersama', 'contact_person' => 'Doni Lesmana', 'address' => 'Jl. Mawar No. 28, Mataram', 'email' => 'karyabersama@gmail.com', 'phone' => '0370-012345'],
            ['company_name' => 'PT Makmur Sejahtera', 'contact_person' => 'Nina Suryani', 'address' => 'Jl. Wijaya No. 41, Jayapura', 'email' => 'makmursejahtera@gmail.com', 'phone' => '0967-123456'],
            ['company_name' => 'PT Prima Jaya', 'contact_person' => 'Herman Susanto', 'address' => 'Jl. Gunung No. 13, Kendari', 'email' => 'primajaya@gmail.com', 'phone' => '0401-234567'],
            ['company_name' => 'PT Surya Kencana', 'contact_person' => 'Tina Amelia', 'address' => 'Jl. Rajawali No. 27, Bandar Lampung', 'email' => 'suryakencana@gmail.com', 'phone' => '0721-345678'],
            ['company_name' => 'PT Mega Mas', 'contact_person' => 'Rudi Hartono', 'address' => 'Jl. Dipatiukur No. 44, Padang', 'email' => 'megamas@gmail.com', 'phone' => '0751-456789'],
            ['company_name' => 'PT Sinar Mentari', 'contact_person' => 'Desi Ratnasari', 'address' => 'Jl. Pramuka No. 8, Jambi', 'email' => 'sinarmentari@gmail.com', 'phone' => '0741-567890'],
            ['company_name' => 'PT Agung Semesta', 'contact_person' => 'Yudi Permana', 'address' => 'Jl. Pattimura No. 31, Bengkulu', 'email' => 'agungsemesta@gmail.com', 'phone' => '0736-678901'],
            ['company_name' => 'PT Bina Karya', 'contact_person' => 'Ani Nuraini', 'address' => 'Jl. Ahmad Yani No. 16, Palu', 'email' => 'binakarya@gmail.com', 'phone' => '0451-789012'],
            ['company_name' => 'PT Cemara Indah', 'contact_person' => 'Fajar Hidayat', 'address' => 'Jl. Kartini No. 22, Gorontalo', 'email' => 'cemaraindah@gmail.com', 'phone' => '0435-890123'],
            ['company_name' => 'PT Darma Utama', 'contact_person' => 'Sari Puspita', 'address' => 'Jl. Gajah Mada No. 37, Ternate', 'email' => 'darmautama@gmail.com', 'phone' => '0921-901234'],
            ['company_name' => 'PT Cahaya Abadi', 'contact_person' => 'Adi Nugroho', 'address' => 'Jl. Veteran No. 5, Ambon', 'email' => 'cahayaabadi@gmail.com', 'phone' => '0911-012345'],
        ];

        foreach ($customers as $customer) {
            Customer::create($customer);
        }
    }
}
