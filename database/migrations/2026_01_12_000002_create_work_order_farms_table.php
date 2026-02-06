<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('work_order_farms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->constrained()->cascadeOnDelete();
            $table->foreignId('work_order_id')->constrained()->cascadeOnDelete();
            $table->string('bloque', 10);
            $table->foreignId('exploitation_id')->nullable()->constrained('farms')->nullOnDelete();
            $table->string('exploitation_name');
            $table->decimal('has', 10, 2);
            $table->decimal('distancia_poblado', 10, 2)->nullable();
            $table->string('line_status', 20)->default('pendiente');
            $table->date('fecha_aplicacion')->nullable();
            $table->timestamps();

            $table->index(['organization_id', 'work_order_id', 'bloque'], 'work_order_farms_org_work_block_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('work_order_farms');
    }
};
