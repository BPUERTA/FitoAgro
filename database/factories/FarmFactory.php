<?php

namespace Database\Factories;

use App\Models\Client;
use App\Models\Farm;
use App\Models\Organization;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Farm>
 */
class FarmFactory extends Factory
{
    protected $model = Farm::class;

    public function definition(): array
    {
        $organization = Organization::factory();

        return [
            'organization_id' => $organization,
            'client_id' => Client::factory()->for($organization),
            'name' => fake()->word() . ' Farm',
            'has' => fake()->randomFloat(2, 1, 100),
            'distancia_poblado' => fake()->randomFloat(2, 0, 20),
            'status' => true,
        ];
    }
}
