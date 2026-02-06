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
            $table->string('mercadopago_plan_id')->nullable()->after('published');
        });

        Schema::table('organizations', function (Blueprint $table) {
            $table->string('mercadopago_subscription_id')->nullable()->after('paid_plan_end_date');
            $table->string('mercadopago_preapproval_id')->nullable()->after('mercadopago_subscription_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('plans', function (Blueprint $table) {
            $table->dropColumn('mercadopago_plan_id');
        });

        Schema::table('organizations', function (Blueprint $table) {
            $table->dropColumn(['mercadopago_subscription_id', 'mercadopago_preapproval_id']);
        });
    }
};
