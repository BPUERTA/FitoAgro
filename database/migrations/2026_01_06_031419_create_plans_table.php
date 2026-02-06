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
        Schema::create('plans', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2);
            $table->string('billing_cycle')->default('monthly'); // monthly, yearly
            $table->integer('max_users')->nullable();
            $table->integer('max_work_orders')->nullable();
            $table->integer('max_farms')->nullable();
            $table->boolean('status')->default(true);
            $table->json('features')->nullable(); // características adicionales
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plans');
    }
};
