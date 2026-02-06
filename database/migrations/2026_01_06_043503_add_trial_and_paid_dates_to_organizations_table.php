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
        Schema::table('organizations', function (Blueprint $table) {
            $table->date('trial_start_date')->nullable()->after('plan_id');
            $table->date('trial_end_date')->nullable()->after('trial_start_date');
            $table->date('paid_plan_start_date')->nullable()->after('trial_end_date');
            $table->date('paid_plan_end_date')->nullable()->after('paid_plan_start_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('organizations', function (Blueprint $table) {
            $table->dropColumn(['trial_start_date', 'trial_end_date', 'paid_plan_start_date', 'paid_plan_end_date']);
        });
    }
};
