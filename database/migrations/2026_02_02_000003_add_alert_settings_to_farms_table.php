<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('farms', function (Blueprint $table) {
            if (!Schema::hasColumn('farms', 'alert_ndvi')) {
                $table->boolean('alert_ndvi')->default(false)->after('lng');
            }
            if (!Schema::hasColumn('farms', 'alert_ndmi')) {
                $table->boolean('alert_ndmi')->default(false)->after('alert_ndvi');
            }
            if (!Schema::hasColumn('farms', 'alert_nbr')) {
                $table->boolean('alert_nbr')->default(false)->after('alert_ndmi');
            }
        });
    }

    public function down(): void
    {
        Schema::table('farms', function (Blueprint $table) {
            $columns = ['alert_ndvi', 'alert_ndmi', 'alert_nbr'];
            foreach ($columns as $column) {
                if (Schema::hasColumn('farms', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
