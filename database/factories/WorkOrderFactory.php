<?php

namespace Database\Factories;

use App\Models\Client;
use App\Models\Organization;
use App\Models\WorkOrder;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<WorkOrder>
 */
class WorkOrderFactory extends Factory
{
    protected $model = WorkOrder::class;

    public function definition(): array
    {
        $organization = Organization::factory();

        return [
            'organization_id' => $organization,
            'client_id' => Client::factory()->for($organization),
            'description' => fake()->sentence(),
            'priority' => WorkOrder::PRIORITY_MEDIUM,
            'status' => WorkOrder::STATUS_PENDIENTE,
            'created_by' => fake()->userName(),
        ];
    }
}
