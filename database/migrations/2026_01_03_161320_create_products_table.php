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
        Schema::create('products', function (Blueprint $table) {
    $table->id();
    $table->string('descripcion');
    $table->string('marca_comercial')->nullable();
    $table->string('principio_activo')->nullable();
    $table->string('formulacion')->nullable();
    $table->string('clase_toxicidad')->nullable();
    $table->string('uso_declarado')->nullable();
    $table->string('um_dosis')->nullable();
    $table->string('um_total')->nullable();
    $table->boolean('status')->default(true);
    $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
