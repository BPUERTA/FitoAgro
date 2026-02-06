<?php

use App\Models\Client;
use App\Models\Organization;
use App\Models\User;
use App\Models\WorkOrder;
use App\Models\WorkOrderFarm;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('no permite ver ordenes de otra organizacion', function () {
    $orgA = Organization::factory()->create();
    $orgB = Organization::factory()->create();

    $userA = User::factory()->for($orgA)->create();
    $clientB = Client::factory()->for($orgB)->create();
    $workOrderB = WorkOrder::factory()->for($orgB)->for($clientB, 'client')->create();

    $this->actingAs($userA)
        ->get(route('work-orders.show', $workOrderB))
        ->assertForbidden();
});

it('cargar fecha_aplicacion cierra la linea', function () {
    $org = Organization::factory()->create();
    $user = User::factory()->for($org)->create(['nickname' => 'tester']);
    $client = Client::factory()->for($org)->create();

    $workOrder = WorkOrder::factory()->for($org)->for($client, 'client')->create([
        'status' => WorkOrder::STATUS_ABIERTO,
    ]);

    $line = WorkOrderFarm::create([
        'organization_id' => $org->id,
        'work_order_id' => $workOrder->id,
        'bloque' => 'A',
        'exploitation_name' => 'Lote 1',
        'has' => 10,
    ]);

    $this->actingAs($user)
        ->postJson(route('work-orders.farms.apply', [$workOrder, $line]), [
            'fecha_aplicacion' => '2026-01-13',
        ])
        ->assertOk();

    $line->refresh();
    expect($line->line_status)->toBe(WorkOrderFarm::STATUS_CERRADO);
    expect($line->fecha_aplicacion?->toDateString())->toBe('2026-01-13');
});

it('si queda una pendiente la ot no se cierra', function () {
    $org = Organization::factory()->create();
    $user = User::factory()->for($org)->create(['nickname' => 'tester']);
    $client = Client::factory()->for($org)->create();

    $workOrder = WorkOrder::factory()->for($org)->for($client, 'client')->create([
        'status' => WorkOrder::STATUS_ABIERTO,
    ]);

    $lineA = WorkOrderFarm::create([
        'organization_id' => $org->id,
        'work_order_id' => $workOrder->id,
        'bloque' => 'A',
        'exploitation_name' => 'Lote 1',
        'has' => 10,
    ]);

    WorkOrderFarm::create([
        'organization_id' => $org->id,
        'work_order_id' => $workOrder->id,
        'bloque' => 'B',
        'exploitation_name' => 'Lote 2',
        'has' => 5,
    ]);

    $this->actingAs($user)
        ->postJson(route('work-orders.farms.apply', [$workOrder, $lineA]), [
            'fecha_aplicacion' => '2026-01-13',
        ])
        ->assertOk();

    $workOrder->refresh();
    expect($workOrder->status)->not->toBe(WorkOrder::STATUS_CERRADO);
});

it('al cerrar la ultima linea la ot se cierra y guarda closed_by', function () {
    $org = Organization::factory()->create();
    $user = User::factory()->for($org)->create(['nickname' => 'tester']);
    $client = Client::factory()->for($org)->create();

    $workOrder = WorkOrder::factory()->for($org)->for($client, 'client')->create([
        'status' => WorkOrder::STATUS_ABIERTO,
    ]);

    $lineA = WorkOrderFarm::create([
        'organization_id' => $org->id,
        'work_order_id' => $workOrder->id,
        'bloque' => 'A',
        'exploitation_name' => 'Lote 1',
        'has' => 10,
    ]);

    $lineB = WorkOrderFarm::create([
        'organization_id' => $org->id,
        'work_order_id' => $workOrder->id,
        'bloque' => 'B',
        'exploitation_name' => 'Lote 2',
        'has' => 5,
    ]);

    $this->actingAs($user)
        ->postJson(route('work-orders.farms.apply', [$workOrder, $lineA]), [
            'fecha_aplicacion' => '2026-01-13',
        ])
        ->assertOk();

    $this->actingAs($user)
        ->postJson(route('work-orders.farms.apply', [$workOrder, $lineB]), [
            'fecha_aplicacion' => '2026-01-14',
        ])
        ->assertOk();

    $workOrder->refresh();
    expect($workOrder->status)->toBe(WorkOrder::STATUS_CERRADO);
    expect($workOrder->closed_by)->toBe('tester');
});

it('ot cancelada nunca se autocierra', function () {
    $org = Organization::factory()->create();
    $admin = User::factory()->for($org)->create([
        'nickname' => 'admin',
        'is_admin' => true,
    ]);
    $client = Client::factory()->for($org)->create();

    $workOrder = WorkOrder::factory()->for($org)->for($client, 'client')->create([
        'status' => WorkOrder::STATUS_CANCELADO,
    ]);

    $line = WorkOrderFarm::create([
        'organization_id' => $org->id,
        'work_order_id' => $workOrder->id,
        'bloque' => 'A',
        'exploitation_name' => 'Lote 1',
        'has' => 10,
    ]);

    $this->actingAs($admin)
        ->postJson(route('work-orders.farms.apply', [$workOrder, $line]), [
            'fecha_aplicacion' => '2026-01-13',
        ])
        ->assertOk();

    $workOrder->refresh();
    expect($workOrder->status)->toBe(WorkOrder::STATUS_CANCELADO);
    expect($workOrder->closed_by)->toBeNull();
});

it('no permite editar lineas en ot cerrada o cancelada salvo admin', function () {
    $org = Organization::factory()->create();
    $user = User::factory()->for($org)->create(['nickname' => 'user']);
    $admin = User::factory()->for($org)->create(['nickname' => 'admin', 'is_admin' => true]);
    $client = Client::factory()->for($org)->create();

    $workOrder = WorkOrder::factory()->for($org)->for($client, 'client')->create([
        'status' => WorkOrder::STATUS_CERRADO,
    ]);

    $line = WorkOrderFarm::create([
        'organization_id' => $org->id,
        'work_order_id' => $workOrder->id,
        'bloque' => 'A',
        'exploitation_name' => 'Lote 1',
        'has' => 10,
    ]);

    $this->actingAs($user)
        ->postJson(route('work-orders.farms.apply', [$workOrder, $line]), [
            'fecha_aplicacion' => '2026-01-13',
        ])
        ->assertForbidden();

    $this->actingAs($admin)
        ->postJson(route('work-orders.farms.apply', [$workOrder, $line]), [
            'fecha_aplicacion' => '2026-01-13',
        ])
        ->assertOk();
});
