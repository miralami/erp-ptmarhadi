<?php

namespace Database\Factories;

use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;

class CustomerFactory extends Factory
{
    protected $model = Customer::class;

    private static array $usedNames = [];

    public function definition(): array
    {
        $names = [
            'PT Sinar Abadi', 'PT Maju Bersama', 'PT Nusantara Jaya',
            'PT Cipta Mandiri', 'PT Bumi Sejahtera', 'PT Karya Utama',
            'PT Indah Purnama', 'PT Harapan Bangsa', 'PT Sentosa Abadi',
            'PT Mitra Sukses', 'PT Delta Pratama', 'PT Kencana Mulia',
            'PT Bintang Timur', 'PT Palma Indah', 'PT Guna Persada',
        ];

        $available = array_values(array_diff($names, self::$usedNames));
        $name = $available[array_rand($available)];
        self::$usedNames[] = $name;

        return [
            'name' => $name,
            'address' => fake()->streetAddress() . ', ' . fake()->city(),
            'email' => strtolower(str_replace([' ', 'PT'], '', $name)) . '@gmail.com',
            'phone' => '021-' . fake()->numerify('#######'),
        ];
    }
}
