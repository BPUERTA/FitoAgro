<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('farm_lots', function (Blueprint $table) {
            if (!Schema::hasColumn('farm_lots', 'polygon_coordinates')) {
                $table->longText('polygon_coordinates')->nullable()->after('vigia_alerts_enabled');
            }
        });
    }

    public function down(): void
    {
        Schema::table('farm_lots', function (Blueprint $table) {
            if (Schema::hasColumn('farm_lots', 'polygon_coordinates')) {
                $table->dropColumn('polygon_coordinates');
            }
        });
    }
};
