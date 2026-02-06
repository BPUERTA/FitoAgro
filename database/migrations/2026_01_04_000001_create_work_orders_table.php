<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('work_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->constrained()->cascadeOnDelete();
            $table->foreignId('client_id')->constrained()->cascadeOnDelete();
            $table->string('number')->unique();
            $table->enum('type', [
                'fumigacion',
                'fertilizacion_liquida',
                'fertilizacion_solida',
                'siembra',
                'cosecha',
                'laboreo'
            ]);
            $table->date('date');
            $table->text('description')->nullable();
            $table->enum('status', ['pendiente', 'en_proceso', 'completada', 'cancelada'])->default('pendiente');
            $table->decimal('total_cost', 10, 2)->nullable();
            $table->timestamps();
        });

        // Tabla pivote para la relación many-to-many entre work_orders y farms
        Schema::create('farm_work_order', function (Blueprint $table) {
            $table->id();
            $table->foreignId('work_order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('farm_id')->constrained()->cascadeOnDelete();
            $table->decimal('area_trabajada', 10, 2)->nullable()->comment('Hectáreas trabajadas en esta explotación');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('farm_work_order');
        Schema::dropIfExists('work_orders');
    }
};
