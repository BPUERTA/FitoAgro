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
        Schema::table('teams', function (Blueprint $table) {
            $table->dropColumn('tipo');
        });
        
        Schema::table('teams', function (Blueprint $table) {
            $table->enum('tipo_equipo', ['fumigacion', 'fertilizacion_liquida', 'fertilizacion_solida', 'siembra', 'cosecha', 'laboreo'])->after('name');
            $table->enum('tipo_propiedad', ['propio', 'contratista'])->default('propio')->after('tipo_equipo');
            $table->text('contratistas')->nullable()->after('tipo_propiedad')->comment('Nombres de contratistas separados por comas');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('teams', function (Blueprint $table) {
            $table->dropColumn(['tipo_equipo', 'tipo_propiedad', 'contratistas']);
            $table->string('tipo')->nullable();
        });
    }
};
