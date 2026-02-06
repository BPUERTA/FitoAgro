<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('work_orders', function (Blueprint $table) {
            if (!Schema::hasColumn('work_orders', 'registro_tecnico_id')) {
                $table->foreignId('registro_tecnico_id')
                    ->nullable()
                    ->after('client_id')
                    ->constrained('registro_tecnicos')
                    ->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('work_orders', function (Blueprint $table) {
            if (Schema::hasColumn('work_orders', 'registro_tecnico_id')) {
                $table->dropForeign(['registro_tecnico_id']);
                $table->dropColumn('registro_tecnico_id');
            }
        });
    }
};
