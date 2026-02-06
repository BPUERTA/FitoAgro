<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('farm_lots', function (Blueprint $table) {
            if (!Schema::hasColumn('farm_lots', 'is_otro')) {
                $table->boolean('is_otro')->default(false)->after('is_ganadera');
            }
        });
    }

    public function down(): void
    {
        Schema::table('farm_lots', function (Blueprint $table) {
            if (Schema::hasColumn('farm_lots', 'is_otro')) {
                $table->dropColumn('is_otro');
            }
        });
    }
};
