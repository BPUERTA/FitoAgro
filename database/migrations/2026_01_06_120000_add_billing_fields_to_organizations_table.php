<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('organizations', function (Blueprint $table) {
            $table->string('billing_name')->nullable();
            $table->string('billing_address')->nullable();
            $table->string('billing_cuit')->nullable();
            $table->string('billing_iva')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('organizations', function (Blueprint $table) {
            $table->dropColumn(['billing_name', 'billing_address', 'billing_cuit', 'billing_iva']);
        });
    }
};
