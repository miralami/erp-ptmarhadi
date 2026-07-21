<?php

namespace Database\Factories;

use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ActivityLogFactory extends Factory
{
    protected $model = ActivityLog::class;

    public function definition(): array
    {
        $modules = ['customer', 'order', 'delivery', 'invoice', 'payment'];
        $actions = ['created', 'updated', 'deleted', 'status_changed'];

        return [
            'user_id' => User::inRandomOrder()->first()?->id,
            'module' => fake()->randomElement($modules),
            'record_id' => fake()->numberBetween(1, 100),
            'action' => fake()->randomElement($actions),
            'description' => fake()->sentence(),
            'old_value' => null,
            'new_value' => null,
        ];
    }
}
