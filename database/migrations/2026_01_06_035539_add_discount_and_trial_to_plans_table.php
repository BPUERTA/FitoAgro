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
        Schema::table('plans', function (Blueprint $table) {
            $table->decimal('annual_discount', 5, 2)->nullable()->after('monthly_price')->comment('Descuento % para pago anual');
            $table->integer('trial_days')->nullable()->after('published')->comment('Días de prueba gratuita');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('plans', function (Blueprint $table) {
            $table->dropColumn(['annual_discount', 'trial_days']);
        });
    }
};
