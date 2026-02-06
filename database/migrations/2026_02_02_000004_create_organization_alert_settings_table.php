<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('organization_alert_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->constrained()->cascadeOnDelete();
            $table->boolean('ndvi_enabled')->default(true);
            $table->boolean('ndmi_enabled')->default(true);
            $table->boolean('nbr_enabled')->default(true);
            $table->decimal('ndvi_drop_threshold', 6, 3)->default(0.10);
            $table->decimal('ndmi_drop_threshold', 6, 3)->default(0.08);
            $table->decimal('nbr_drop_threshold', 6, 3)->default(0.12);
            $table->unsignedInteger('cloud_max_percent')->default(40);
            $table->string('frequency')->default('weekly');
            $table->timestamps();

            $table->unique('organization_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('organization_alert_settings');
    }
};
