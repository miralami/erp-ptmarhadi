<?php

namespace Database\Factories;

use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;

class CustomerFactory extends Factory
{
    protected $model = Customer::class;

    public function definition(): array
    {
        $prefixes = ['PT', 'CV', 'UD'];
        $names = ['Sinar', 'Maju', 'Nusantara', 'Cipta', 'Bumi', 'Karya', 'Indah',
            'Harapan', 'Sentosa', 'Mitra', 'Delta', 'Kencana', 'Bintang', 'Palma',
            'Guna', 'Aneka', 'Sumber', 'Cahaya', 'Duta', 'Makmur', 'Prima',
            'Roda', 'Tunas', 'Usaha', 'Wahana', 'Yasa', 'Zamrud', 'Bahana',
            'Agung', 'Bhakti', 'Citra', 'Dharma', 'Eka', 'Gita', 'Inti',
        ];

        return [
            'company_name' => $prefixes[array_rand($prefixes)] . ' ' . $names[array_rand($names)] . ' ' . fake()->randomElement(['Abadi', 'Jaya', 'Sejahtera', 'Mandiri', 'Utama', 'Bersama', 'Mulia', 'Sukses']),
            'contact_person' => fake()->name(),
            'address' => fake()->streetAddress() . ', ' . fake()->city(),
            'email' => fake()->unique()->safeEmail(),
            'phone' => '021-' . fake()->numerify('#######'),
            'notes' => fake()->optional(0.3)->sentence(),
        ];
    }
}
