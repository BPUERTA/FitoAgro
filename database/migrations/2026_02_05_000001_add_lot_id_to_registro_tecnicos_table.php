<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('registro_tecnicos', function (Blueprint $table) {
            if (!Schema::hasColumn('registro_tecnicos', 'lot_id')) {
                $table->foreignId('lot_id')
                    ->nullable()
                    ->after('farm_id')
                    ->constrained('farm_lots')
                    ->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('registro_tecnicos', function (Blueprint $table) {
            if (Schema::hasColumn('registro_tecnicos', 'lot_id')) {
                $table->dropConstrainedForeignId('lot_id');
            }
        });
    }
};
