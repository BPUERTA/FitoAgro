<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('work_order_products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->constrained()->cascadeOnDelete();
            $table->foreignId('work_order_id')->constrained()->cascadeOnDelete();
            $table->string('bloque', 10);
            $table->decimal('total_has_bloque', 10, 2);
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->string('product_name');
            $table->decimal('dosis', 10, 2);
            $table->string('um_dosis', 20);
            $table->decimal('total_linea_producto', 10, 2);
            $table->string('um_total', 20);
            $table->string('nota')->nullable();
            $table->decimal('precio_unitario', 10, 2)->nullable();
            $table->decimal('total_costo_linea', 12, 2)->nullable();
            $table->timestamps();

            $table->unique(
                ['organization_id', 'work_order_id', 'bloque', 'product_id'],
                'work_order_products_org_work_block_product_unique'
            );
            $table->index(['work_order_id', 'bloque'], 'work_order_products_work_block_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('work_order_products');
    }
};
