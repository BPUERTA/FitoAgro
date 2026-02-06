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
        Schema::table('work_orders', function (Blueprint $table) {
            // Eliminar el índice único global
            $table->dropUnique(['number']);
            
            // Crear un índice único compuesto (number + organization_id)
            $table->unique(['organization_id', 'number'], 'work_orders_org_number_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('work_orders', function (Blueprint $table) {
            // Revertir: eliminar el índice compuesto
            $table->dropUnique('work_orders_org_number_unique');
            
            // Restaurar el índice único global
            $table->unique('number');
        });
    }
};
