<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        return [
            'descripcion' => fake()->word(),
            'um_dosis' => 'L/ha',
            'um_total' => 'L',
            'status' => true,
        ];
    }
}
