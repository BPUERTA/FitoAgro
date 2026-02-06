<?php

namespace Database\Factories;

use App\Models\Farm;
use App\Models\WorkOrder;
use App\Models\WorkOrderFarm;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<WorkOrderFarm>
 */
class WorkOrderFarmFactory extends Factory
{
    protected $model = WorkOrderFarm::class;

    public function definition(): array
    {
        $organization = \App\Models\Organization::factory();

        return [
            'organization_id' => $organization,
            'work_order_id' => WorkOrder::factory()
                ->for($organization)
                ->for(\App\Models\Client::factory()->for($organization), 'client'),
            'bloque' => 'A',
            'exploitation_id' => Farm::factory()
                ->for($organization)
                ->for(\App\Models\Client::factory()->for($organization), 'client'),
            'exploitation_name' => fake()->word(),
            'has' => fake()->randomFloat(2, 1, 50),
            'distancia_poblado' => fake()->randomFloat(2, 0, 20),
            'line_status' => WorkOrderFarm::STATUS_PENDIENTE,
            'fecha_aplicacion' => null,
        ];
    }
}
